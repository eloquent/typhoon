<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'Exception.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Type.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'BaseType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Composite'.DIRECTORY_SEPARATOR.'CompositeType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Composite'.DIRECTORY_SEPARATOR.'BaseCompositeType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Composite'.DIRECTORY_SEPARATOR.'AndType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Composite'.DIRECTORY_SEPARATOR.'OrType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Traversable'.DIRECTORY_SEPARATOR.'TraversableType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Traversable'.DIRECTORY_SEPARATOR.'BaseTraversableType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'ArrayType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'BooleanType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'CallbackType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'FloatType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'IntegerType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'MixedType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'NullType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'ParameterType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'ResourceType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'StringType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'TypeType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Primitive'.DIRECTORY_SEPARATOR.'Primitive.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Primitive'.DIRECTORY_SEPARATOR.'Boolean.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Primitive'.DIRECTORY_SEPARATOR.'Callback.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Primitive'.DIRECTORY_SEPARATOR.'Integer.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Primitive'.DIRECTORY_SEPARATOR.'Null.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Primitive'.DIRECTORY_SEPARATOR.'String.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Collection'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'Exception.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Collection'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UndefinedKeyException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Collection'.DIRECTORY_SEPARATOR.'Collection.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Attribute'.DIRECTORY_SEPARATOR.'AttributeHolder.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Attribute'.DIRECTORY_SEPARATOR.'AttributeSignature.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Attribute'.DIRECTORY_SEPARATOR.'Attributes.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Dynamic'.DIRECTORY_SEPARATOR.'DynamicType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Dynamic'.DIRECTORY_SEPARATOR.'BaseDynamicType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'CallbackWrapperType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'ObjectType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'TraversableType.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Inspector'.DIRECTORY_SEPARATOR.'TypeInspector.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Registry'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'Exception.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Registry'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnregisteredTypeAliasException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Registry'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnregisteredTypeException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Registry'.DIRECTORY_SEPARATOR.'TypeRegistry.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Renderer'.DIRECTORY_SEPARATOR.'TypeRenderer.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Type'.DIRECTORY_SEPARATOR.'Renderer'.DIRECTORY_SEPARATOR.'TyphaxTypeRenderer.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'Exception.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'MissingArgumentException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'MissingAttributeException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnexpectedArgumentException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnexpectedAttributeException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnexpectedTypeException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnsupportedAttributeException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'Assertion.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'ParameterAssertion.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'ParameterListAssertion.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Assertion'.DIRECTORY_SEPARATOR.'TypeAssertion.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'NotImplementedException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Parameter'.DIRECTORY_SEPARATOR.'ParameterList'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'Exception.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Parameter'.DIRECTORY_SEPARATOR.'ParameterList'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UndefinedParameterException.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Parameter'.DIRECTORY_SEPARATOR.'ParameterList'.DIRECTORY_SEPARATOR.'ParameterList.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Parameter'.DIRECTORY_SEPARATOR.'Parameter.php';
require __DIR__.DIRECTORY_SEPARATOR.'Ezzatron'.DIRECTORY_SEPARATOR.'Typhoon'.DIRECTORY_SEPARATOR.'Typhoon.php';