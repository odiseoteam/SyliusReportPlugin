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

final class ProductSearchAction
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private LocaleContextInterface $localeContext,
        private ConfigurableViewHandlerInterface $viewHandler,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $locale = $this->localeContext->getLocaleCode();

        /** @var string $query */
        $query = $request->query->get('name', '');

        $products = $this->getProducts($query, $locale);
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
