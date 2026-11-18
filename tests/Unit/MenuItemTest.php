<?php

/*
 * MenuItem extends App\Models\BaseModel, a class that lives in the host
 * application rather than in this package. Provide a minimal stand-in so the
 * model can be instantiated in isolation during unit tests. Because this file
 * mixes namespaces it uses the braced namespace syntax throughout.
 */

namespace App\Models {
    use Illuminate\Database\Eloquent\Model;

    class BaseModel extends Model
    {
    }
}

namespace Nasirkhan\ModuleManager\Tests\Unit {
    use Illuminate\Http\Request;
    use Illuminate\Routing\Route;
    use Illuminate\Support\Collection;
    use Nasirkhan\ModuleManager\Modules\Menu\Models\MenuItem;
    use Nasirkhan\ModuleManager\Tests\TestCase;

    class MenuItemTest extends TestCase
    {
        /**
         * Two items sharing a route name but differing by a slug parameter:
         * only the item whose parameters match the current route is active.
         */
        public function test_only_item_with_matching_route_parameters_is_active(): void
        {
            $this->setCurrentRoute('frontend.pages.show', '/pages/{slug}', ['slug' => 'about'], '/pages/about');

            $about = $this->makeItem([
                'route_name' => 'frontend.pages.show',
                'route_parameters' => ['slug' => 'about'],
            ]);

            $features = $this->makeItem([
                'route_name' => 'frontend.pages.show',
                'route_parameters' => ['slug' => 'features'],
            ]);

            $this->assertTrue($about->isCurrentlyActive());
            $this->assertFalse($features->isCurrentlyActive());
        }

        /**
         * An item with no route_parameters stays active on a route-name match.
         */
        public function test_item_without_parameters_is_active_on_route_name_match(): void
        {
            $this->setCurrentRoute('frontend.pages.show', '/pages/{slug}', ['slug' => 'about'], '/pages/about');

            $item = $this->makeItem([
                'route_name' => 'frontend.pages.show',
                'route_parameters' => null,
            ]);

            $this->assertTrue($item->isCurrentlyActive());
        }

        /**
         * URL-based matching still works for items without a route name.
         */
        public function test_item_is_active_when_url_matches_request(): void
        {
            $this->setCurrentRoute('frontend.pages.show', '/pages/{slug}', ['slug' => 'about'], '/pages/about');

            $item = $this->makeItem([
                'route_name' => null,
                'url' => '/pages/about',
            ]);

            $this->assertTrue($item->isCurrentlyActive());
        }

        /**
         * A parent remains active when one of its children matches the current route,
         * even though the parent's own route differs by parameter.
         */
        public function test_item_is_active_when_a_child_is_active(): void
        {
            $this->setCurrentRoute('frontend.pages.show', '/pages/{slug}', ['slug' => 'features'], '/pages/features');

            $child = $this->makeItem([
                'route_name' => 'frontend.pages.show',
                'route_parameters' => ['slug' => 'features'],
            ]);

            $parent = $this->makeItem([
                'route_name' => 'frontend.pages.show',
                'route_parameters' => ['slug' => 'about'],
            ], collect([$child]));

            // The parent's own parameters do not match the current route...
            $this->assertNotEquals('about', request()->route()->parameter('slug'));
            // ...but it is active because its child matches.
            $this->assertTrue($parent->isCurrentlyActive());
        }

        /**
         * Build a MenuItem without touching the database, with an (optionally
         * populated) children relation preloaded so isCurrentlyActive() never
         * triggers a query.
         */
        private function makeItem(array $attributes, ?Collection $children = null): MenuItem
        {
            $item = new MenuItem;

            foreach ($attributes as $key => $value) {
                $item->{$key} = $value;
            }

            $item->setRelation('children', $children ?? collect());

            return $item;
        }

        /**
         * Replace the application's current request/route with a fake one so
         * request()->route() and request()->is() behave as if that route was hit.
         */
        private function setCurrentRoute(string $name, string $uri, array $parameters, string $path): void
        {
            $request = Request::create($path, 'GET');

            $route = new Route('GET', $uri, []);
            $route->name($name);
            $route->bind($request);

            foreach ($parameters as $key => $value) {
                $route->setParameter($key, $value);
            }

            $request->setRouteResolver(fn () => $route);

            $this->app->instance('request', $request);
        }
    }
}
