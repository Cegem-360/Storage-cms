<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Category;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryCategories implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query product categories. Can search by name or code. Returns category details including product count.';
    }

    public function handle(Request $request): string
    {
        $query = Category::query()
            ->where('categories.team_id', $this->team->id)
            ->with(['parent:id,name'])
            ->withCount('products');

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $categories = $query->limit(30)->get();

        if ($categories->isEmpty()) {
            return 'Nem található kategória a megadott szűrőkkel.';
        }

        $result = "Kategóriák ({$categories->count()} tétel):\n\n";

        foreach ($categories as $category) {
            $parent = $category->parent?->name ?? '-';
            $result .= "- {$category->name} (Kód: {$category->code}) | "
                ."Szülő: {$parent} | "
                ."Termékek száma: {$category->products_count}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by category name or code'),
        ];
    }
}
