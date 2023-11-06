<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->ruleWithConfiguration(
		RenameMethodRector::class,
		[
			// @see https://github.com/smf/smf/pull/6626
			new MethodCallRename(
				'smf\Component\Security\Authentication\UserInterface',
				'getUsername',
				'getUserIdentifier',
			),
		],
	);

	$rectorConfig->ruleWithConfiguration(
		AddReturnTypeDeclarationRector::class,
		[
			// @see https://github.com/smf/smf/pull/6582
			new AddReturnTypeDeclaration(
				'smf\Bundle\SecurityBundle\Entity\User',
				'getRoles',
				new ArrayType(new IntegerType(), new StringType()),
			),
			new AddReturnTypeDeclaration(
				'smf\Bundle\SecurityBundle\Entity\User',
				'isEqualTo',
				new BooleanType(),
			),
			// @see https://github.com/smf/smf/pull/6601
			new AddReturnTypeDeclaration(
				'smf\Bundle\HttpCacheBundle\Cache\smfHttpCache',
				'fetch',
				new ObjectType('Symfony\Component\HttpFoundation\Response'),
			),
			new AddReturnTypeDeclaration(
				'smf\Bundle\HttpCacheBundle\Cache\smfHttpCache',
				'createStore',
				new ObjectType('Symfony\Component\HttpKernel\HttpCache\StoreInterface'),
			),
			// @see https://github.com/smf/smf/pull/6583
			new AddReturnTypeDeclaration(
				'smf\Bundle\SecurityBundle\Security\AuthenticationHandler',
				'onAuthenticationSuccess',
				new ObjectType('Symfony\Component\HttpFoundation\Response'),
			),
			new AddReturnTypeDeclaration(
				'smf\Bundle\SecurityBundle\Security\AuthenticationHandler',
				'onAuthenticationFailure',
				new ObjectType('Symfony\Component\HttpFoundation\Response'),
			),
			// @see https://github.com/smf/smf/pull/6580
			new AddReturnTypeDeclaration(
				'smf\Bundle\WebsiteBundle\Controller\WebsiteController',
				'getSubscribedServices',
				new ArrayType(new StringType(), new StringType()),
			),
			// @see https://github.com/smf/smf/pull/6562
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'registerBundles',
				new IterableType(new StringType(), new StringType()),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getContainerClass',
				new StringType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getCacheDir',
				new StringType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getCommonCacheDir',
				new StringType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getLogDir',
				new StringType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getKernelParameters',
				new ArrayType(new StringType(), new StringType()),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getEnvironment',
				new StringType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'isDebug',
				new BooleanType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getCharset',
				new StringType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getStartTime',
				new FloatType(),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getContainer',
				new ObjectType('Symfony\Component\DependencyInjection\ContainerInterface'),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getBundle',
				new ObjectType('Symfony\Component\HttpKernel\Bundle\BundleInterface'),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'getBundles',
				new ArrayType(new IntegerType(), new ObjectType('Symfony\Component\HttpKernel\Bundle\BundleInterface')),
			),
			new AddReturnTypeDeclaration(
				'smf\Component\HttpKernel\smfKernel',
				'locateResource',
				new StringType(),
			),
			// @see https://github.com/smf/smf/pull/6581
			new AddReturnTypeDeclaration(
				'Symfony\Cmf\Component\Routing\RouteProviderInterface',
				'getRouteCollectionForRequest',
				new ObjectType('Symfony\Component\Routing\RouteCollection'),
			),
			new AddReturnTypeDeclaration(
				'Symfony\Cmf\Component\Routing\RouteProviderInterface',
				'getRouteByName',
				new ObjectType('Symfony\Component\Routing\Route'),
			),
			new AddReturnTypeDeclaration(
				'Symfony\Cmf\Component\Routing\RouteProviderInterface',
				'getRoutesByNames',
				new IterableType(new StringType(), new ObjectType('Symfony\Component\Routing\RouteCollection')),
			),
			// @see https://github.com/smf/smf/pull/6581
			new AddReturnTypeDeclaration(
				'Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface',
				'enhance',
				new ArrayType(new MixedType(), new MixedType()),
			),
		],
	);
};
