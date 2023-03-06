<?php

namespace App\Console\Commands;

use App\Components\Services\CurrencyApiService;
use App\Events\CurrencyEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use JsonException;

class GetCurrencies extends Command
{
    private CurrencyApiService $currencyApiService;

    public function __construct(CurrencyApiService $currencyApiService)
    {
        parent::__construct();
        $this->currencyApiService = $currencyApiService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Getting currencies from API every 5 minute';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws JsonException
     */
    public function handle() :void
    {
        $response = $this->currencyApiService->getData();

        if ($response) {
            $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            CurrencyEvent::dispatch(array_slice($data, 0, count($data) / 2));
            CurrencyEvent::dispatch(array_slice($data, count($data) / 2));
        } else {
            Log::info('Waiting for new data');
        }
    }
}
