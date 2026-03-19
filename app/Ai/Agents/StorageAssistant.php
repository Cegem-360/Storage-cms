<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\Ai\Tools\QueryBatches;
use App\Ai\Tools\QueryCategories;
use App\Ai\Tools\QueryCnCodes;
use App\Ai\Tools\QueryCustomers;
use App\Ai\Tools\QueryEmployees;
use App\Ai\Tools\QueryIntrastatDeclarations;
use App\Ai\Tools\QueryInventories;
use App\Ai\Tools\QueryInvoices;
use App\Ai\Tools\QueryOrders;
use App\Ai\Tools\QueryProducts;
use App\Ai\Tools\QueryReceipts;
use App\Ai\Tools\QueryReturnDeliveries;
use App\Ai\Tools\QueryStockLevels;
use App\Ai\Tools\QuerySuppliers;
use App\Ai\Tools\QueryWarehouses;
use App\Models\Team;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;

#[Provider('gemini')]
#[Model('gemini-2.5-flash')]
#[MaxSteps(10)]
#[MaxTokens(4096)]
#[Temperature(0.7)]
#[Timeout(120)]
final class StorageAssistant implements Agent, Conversational, HasTools
{
    use Promptable;
    use RemembersConversations;

    public function __construct(public Team $team) {}

    public function instructions(): string
    {
        return 'Te egy raktárkezelő és beszerzés-logisztikai rendszer AI asszisztense vagy. '
            .'A rendszer neve "Storage CMS" és a következő modulokat kezeli: '
            .'Termékek, Kategóriák, Beszállítók, Raktárak, Készletek, Sarzsok, '
            .'Rendelések, Bevételezések, Visszáruk, Leltárak, Intrastat nyilatkozatok. '
            .'Használd a rendelkezésedre álló eszközöket (tools) a valós adatok lekérdezéséhez. '
            .'Válaszolj magyarul, tömören és szakszerűen. '
            .'Segíts a felhasználónak a rendszer használatában, készletkezelési kérdésekben, '
            .'beszerzési tanácsokkal és logisztikai optimalizálásban. '
            .'A jelenlegi cég: "'.$this->team->name.'".';
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new QueryStockLevels($this->team),
            new QueryProducts($this->team),
            new QueryOrders($this->team),
            new QuerySuppliers($this->team),
            new QueryCategories($this->team),
            new QueryWarehouses($this->team),
            new QueryBatches($this->team),
            new QueryInventories($this->team),
            new QueryReceipts($this->team),
            new QueryReturnDeliveries($this->team),
            new QueryCustomers($this->team),
            new QueryEmployees($this->team),
            new QueryInvoices($this->team),
            new QueryCnCodes(),
            new QueryIntrastatDeclarations($this->team),
        ];
    }
}
