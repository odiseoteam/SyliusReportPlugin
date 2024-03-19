<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Controller;

use Doctrine\Persistence\ObjectManager;
use FOS\RestBundle\View\View;
use Odiseo\SyliusReportPlugin\Controller\ReportController;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class ReportControllerSpec extends ObjectBehavior
{
    function let(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        ViewHandlerInterface $viewHandler,
        RepositoryInterface $repository,
        FactoryInterface $factory,
        NewResourceFactoryInterface $newResourceFactory,
        ObjectManager $manager,
        SingleResourceProviderInterface $singleResourceProvider,
        ResourcesCollectionProviderInterface $resourcesFinder,
        ResourceFormFactoryInterface $resourceFormFactory,
        RedirectHandlerInterface $redirectHandler,
        FlashHelperInterface $flashHelper,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher,
        StateMachineInterface $stateMachine,
        ResourceUpdateHandlerInterface $resourceUpdateHandler,
        ResourceDeleteHandlerInterface $resourceDeleteHandler
    )
    {
        $this->beConstructedWith(
            $metadata, $requestConfigurationFactory, $viewHandler, $repository, $factory, $newResourceFactory,
            $manager, $singleResourceProvider, $resourcesFinder, $resourceFormFactory, $redirectHandler, $flashHelper,
            $authorizationChecker, $eventDispatcher, $stateMachine, $resourceUpdateHandler, $resourceDeleteHandler
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ReportController::class);
    }

    function it_should_extends_resource_controller_interface()
    {
        $this->shouldBeAnInstanceOf(ResourceController::class);
    }

    function it_throws_a_403_exception_if_user_is_unauthorized_to_render_the_report(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        RequestConfiguration $configuration,
        Request $request,
        AuthorizationCheckerInterface $authorizationChecker
    ): void {
        $requestConfigurationFactory->create($metadata, $request)->willReturn($configuration);
        $configuration->hasPermission()->willReturn(true);
        $configuration->getPermission(ResourceActions::SHOW)->willReturn('odiseo_sylius_report.report.show');

        $authorizationChecker->isGranted($configuration, 'odiseo_sylius_report.report.show')->willReturn(false);

        $this
            ->shouldThrow(new AccessDeniedException())
            ->during('renderAction', [$request])
        ;
    }

    function it_returns_a_response_for_non_html_view_of_single_resource(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        RequestConfiguration $configuration,
        AuthorizationCheckerInterface $authorizationChecker,
        RepositoryInterface $repository,
        SingleResourceProviderInterface $singleResourceProvider,
        ResourceInterface $resource,
        ViewHandlerInterface $viewHandler,
        EventDispatcherInterface $eventDispatcher,
        Request $request,
        Response $response
    ): void {
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $requestConfigurationFactory->create($metadata, $request)->willReturn($configuration);
        $configuration->hasPermission()->willReturn(true);
        $configuration->getPermission(ResourceActions::SHOW)->willReturn('sylius.product.show');

        $authorizationChecker->isGranted($configuration, 'sylius.product.show')->willReturn(true);
        $singleResourceProvider->get($configuration, $repository)->willReturn($resource);

        $configuration->isHtmlRequest()->willReturn(false);

        $eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $resource)->shouldBeCalled();

        $expectedView = View::create($resource);

        $viewHandler->handle($configuration, Argument::that($this->getViewComparingCallback($expectedView)))->willReturn($response);

        $this->showAction($request)->shouldReturn($response);
    }

    private function getViewComparingCallback(View $expectedView)
    {
        return function ($value) use ($expectedView) {
            if (!$value instanceof View) {
                return false;
            }

            // Need to unwrap phpspec's Collaborators to ensure proper comparison.
            $this->unwrapViewData($expectedView);
            $this->nullifyDates($value);
            $this->nullifyDates($expectedView);

            return
                $expectedView->getStatusCode() === $value->getStatusCode() &&
                $expectedView->getHeaders() === $value->getHeaders() &&
                $expectedView->getFormat() === $value->getFormat() &&
                $expectedView->getData() === $value->getData()
            ;
        };
    }

    /**
     * @param View $view
     */
    private function unwrapViewData(View $view)
    {
        $view->setData($this->unwrapIfCollaborator($view->getData()));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private function unwrapIfCollaborator($value)
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof Collaborator) {
            return $value->getWrappedObject();
        }

        if (is_array($value)) {
            foreach ($value as $key => $childValue) {
                $value[$key] = $this->unwrapIfCollaborator($childValue);
            }
        }

        return $value;
    }

    /**
     * @param View $view
     */
    private function nullifyDates(View $view)
    {
        $headers = $view->getHeaders();
        unset($headers['date']);
        $view->setHeaders($headers);
    }
}
