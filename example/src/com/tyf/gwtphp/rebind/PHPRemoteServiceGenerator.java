/*
 * GWTPHP is a port to PHP of the GWT RPC package.
 * This framework is based on GWT.
 * Design, strategies and part of the methods documentation are developed by Google Inc.
 * PHP port, extensions and modifications by Rafal M.Malinowski. All rights reserved.
 * Additional modifications, GWT generators and linkers by Yifei Teng. All rights reserved.
 * For more information, please see {@link https://github.com/tengyifei/gwtphp}
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
package com.tyf.gwtphp.rebind;

import java.util.Collection;
import java.util.HashSet;
import java.util.Set;

import com.google.gwt.core.ext.GeneratorContext;
import com.google.gwt.core.ext.RebindResult;
import com.google.gwt.core.ext.TreeLogger;
import com.google.gwt.core.ext.UnableToCompleteException;
import com.google.gwt.core.ext.typeinfo.JClassType;
import com.google.gwt.core.ext.typeinfo.JField;
import com.google.gwt.core.ext.typeinfo.JMethod;
import com.google.gwt.core.ext.typeinfo.JType;
import com.google.gwt.core.ext.typeinfo.TypeOracle;
import com.google.gwt.user.rebind.rpc.ProxyCreator;
import com.google.gwt.user.rebind.rpc.ServiceInterfaceProxyGenerator;
import com.tyf.gwtphp.linker.RPCFunction;
import com.tyf.gwtphp.linker.RPCField;
import com.tyf.gwtphp.linker.RPCObjectArtifact;
import com.tyf.gwtphp.linker.RPCServiceArtifact;

public class PHPRemoteServiceGenerator extends ServiceInterfaceProxyGenerator {

	private static final Set<JType> customObjectSet = new HashSet<JType>();
	private static final Set<String> generatedClasses = new HashSet<String>();

	public RebindResult generateIncrementallyOrig(TreeLogger logger, GeneratorContext ctx,
			String requestedClass) throws UnableToCompleteException {

		TypeOracle typeOracle = ctx.getTypeOracle();
		assert (typeOracle != null);

		JClassType remoteService = typeOracle.findType(requestedClass);
		if (remoteService == null) {
			logger.log(TreeLogger.ERROR, "Unable to find metadata for type '" + requestedClass
					+ "'", null);
			throw new UnableToCompleteException();
		}

		if (remoteService.isInterface() == null) {
			logger.log(TreeLogger.ERROR, remoteService.getQualifiedSourceName()
					+ " is not an interface", null);
			throw new UnableToCompleteException();
		}

		ProxyCreator proxyCreator = createProxyCreator(remoteService);

		TreeLogger proxyLogger = logger.branch(
				TreeLogger.DEBUG,
				"Generating client proxy for remote service interface '"
						+ remoteService.getQualifiedSourceName() + "'", null);

		return proxyCreator.create(proxyLogger, ctx);
	}

	@Override
	public RebindResult generateIncrementally(TreeLogger logger, GeneratorContext ctx,
			String requestedClass) throws UnableToCompleteException {

		TypeOracle typeOracle = ctx.getTypeOracle();
		String qualifiedClassName;
		String packageName, className;

		try {
			// get classType and save instance variables
			JClassType classType = typeOracle.getType(requestedClass);
			packageName = classType.getPackage().getName();
			className = classType.getSimpleSourceName();
			qualifiedClassName = packageName + "." + className;
			// prevent rediscovery
			if (generatedClasses.contains(qualifiedClassName))
				generateIncrementallyOrig(logger, ctx, requestedClass);

			RPCServiceArtifact artifact = new RPCServiceArtifact(
					classType.getQualifiedSourceName(), classType.getSimpleSourceName());

			// discover new custom objects, whose information must be known by
			// the server
			Set<RPCObjectArtifact> objectArtifacts = new HashSet<RPCObjectArtifact>();
			Set<JType> discoveredTypes = new HashSet<JType>();

			JMethod[] methods = classType.getMethods();
			// parse RPC methods
			for (JMethod method : methods) {
				JType returnType = method.getReturnType();
				JType[] paramTypes = method.getParameterTypes();
				String[] params = new String[paramTypes.length];
				String[] paramNames = new String[params.length];

				// getRpcTypeName recursively generates the type name, while
				// adding all
				// discovered types to the set, flattening out arrays &
				// generics, etc.
				String returnTypeName = TypeUtil.getPHPRpcTypeName(returnType, discoveredTypes);
				for (int i = 0; i < params.length; i++) {
					params[i] = TypeUtil.getPHPRpcTypeName(paramTypes[i], discoveredTypes);
					paramNames[i] = method.getParameters()[i].getName();
				}

				// get type signature of the return type
				String returnTypeCRC = TypeUtil.getCRC(returnType);

				RPCFunction f = new RPCFunction(method.getName(), returnTypeName, returnTypeCRC,
						params, paramNames, new String[0]);

				artifact.putMethod(method.getName(), f);
			}
			for (JType type : discoveredTypes) {
				// logger.log(TreeLogger.INFO, type.getQualifiedSourceName());
				objectArtifacts.addAll(discoverObjects(type));
			}

			ctx.commitArtifact(logger, artifact);
			for (RPCObjectArtifact a : objectArtifacts) {
				ctx.commitArtifact(logger, a);
			}
		} catch (Exception e) {
			logger.log(TreeLogger.ERROR, "ERROR", e);
			return null;
		}

		return generateIncrementallyOrig(logger, ctx, requestedClass);
	}

	private Collection<? extends RPCObjectArtifact> discoverObjects(JType type)
			throws ClassNotFoundException {
		Set<RPCObjectArtifact> objects = new HashSet<RPCObjectArtifact>();
		// reduce time wasted doing duplicate discovery
		if (customObjectSet.contains(type))
			return objects;

		Set<JType> discoveredTypes = new HashSet<JType>();
		if (isCustom(type)) {
			RPCObjectArtifact object = new RPCObjectArtifact(type.getQualifiedSourceName(),
					type.getSimpleSourceName(), TypeUtil.getCRC(type));
			JClassType classType;
			if ((classType = type.isClass()) != null) {
				for (JField f : classType.getFields()) {
					String fieldName = f.getName();
					String fieldType = TypeUtil.getPHPRpcTypeName(f.getType(), discoveredTypes);
					object.putField(fieldName, new RPCField(fieldName, fieldType, 
							TypeUtil.toPHPType(f.getType())));
				}
			}
			objects.add(object);
			customObjectSet.add(type);
		}
		// recursively discover other custom objects refereced by this object
		for (JType t : discoveredTypes) {
			objects.addAll(discoverObjects(t));
		}
		return objects;
	}

	/**
	 * Checks if a JType is built-in or user-defined
	 * 
	 * @param returnType
	 * @return
	 */
	private boolean isCustom(JType returnType) {
		if (returnType.isPrimitive() != null)
			return false;
		// exclude built-in Java classes
		if (returnType.getQualifiedSourceName().startsWith("java."))
			return false;
		return true;
	}

}