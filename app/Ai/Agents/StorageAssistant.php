<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\Ai\Tools\QueryOrders;
use App\Ai\Tools\QueryProducts;
use App\Ai\Tools\QueryStockLevels;
use App\Ai\Tools\QuerySuppliers;
use App\Models\Team;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;

#[MaxTokens(2048)]
#[Temperature(0.7)]
final class StorageAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    /** @var array<int, array{role: string, content: string}> */
    private array $conversationHistory = [];

    public function __construct(public Team $team) {}

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    public function withHistory(array $history): self
    {
        $this->conversationHistory = $history;

        return $this;
    }

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

    public function messages(): iterable
    {
        return collect($this->conversationHistory)
            ->map(fn (array $msg): Message => new Message($msg['role'], $msg['content']))
            ->all();
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
        ];
    }
}
