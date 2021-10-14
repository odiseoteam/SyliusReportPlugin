<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Controller\Action;

use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
final class ProductSearchAction
{
    private ProductRepositoryInterface $productRepository;
    private LocaleContextInterface $localeContext;
    private ConfigurableViewHandlerInterface $viewHandler;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        LocaleContextInterface $localeContext,
        ConfigurableViewHandlerInterface $viewHandler
    ) {
        $this->productRepository = $productRepository;
        $this->localeContext = $localeContext;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(Request $request): Response
    {
        $locale = $this->localeContext->getLocaleCode();

        $products = $this->getProducts($request->get('name', ''), $locale);
        $view = View::create($products);

        $this->viewHandler->setExclusionStrategyGroups(['Autocomplete']);
        $view->getContext()->enableMaxDepth();

        return $this->viewHandler->handle($view);
    }

    private function getProducts(string $query, string $locale): array
    {
        $products = [];
        $searchProducts = $this->productRepository->findByNamePart($query, $locale);

        /** @var ProductInterface $product */
        foreach ($searchProducts as $product) {
            $productLabel = ucfirst(strtolower($product->getName() ?? ''));
            $isNew = count(array_filter($products, function ($product) use ($productLabel): bool {
                return $product['name'] === $productLabel;
            })) === 0;

            if ($isNew) {
                $products[] = [
                    'name' => $productLabel,
                    'id' => $product->getId(),
                ];
            }
        }

        return $products;
    }
}
