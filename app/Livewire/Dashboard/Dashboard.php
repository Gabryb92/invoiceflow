<?php

namespace App\Livewire\Dashboard;

use App\Models\Client;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{

    public function get_total_revenue(){
        return Invoice::where('status', 'paid')->whereYear('issue_date', date(('Y')))->sum('total');
    }

    public function get_outstanding_amount(){
        return Invoice::whereIn('status', ['unpaid', 'partially_paid'])->sum('total');
    }

    public function get_active_clients_counts(){
       return Client::count();
    }

    public function get_upcoming_due_invoices_count(){
       return Invoice::where('status', 'unpaid')->whereBetween('due_date', [now(), now()->addDays(30)])->count();
    }

   public function get_latest_invoices()
    {
        return Invoice::with('client')->latest()->take(5)->get();
    }

    public function get_recently_added_clients()
    {
        return Client::latest()->take(5)->get();
    }


    public function get_monthly_revenue(){
        // Calcolo dinamico: partendo dal mese corrente, andiamo indietro di 5 mesi
        // Luglio 2025 -> indietro 5 mesi = Febbraio 2025
        // Agosto 2025 -> indietro 5 mesi = Marzo 2025, ecc.


        // 1. OTTENERE IL MESE CORRENTE
        // now() = data di oggi (29 luglio 2025, 14:30:25)
        // startOfMonth() = primo giorno del mese corrente (1 luglio 2025, 00:00:00)
        $currentMonth = now()->startOfMonth(); // Es: 2025-07-01
        //dd($currentMonth);


        // 2. CALCOLARE IL MESE DI PARTENZA
        // copy() = crea una COPIA della data (senza modificare l'originale)
        // Perché copy()? Perché se non lo fai, modifichi $currentMonth!
        // 
        // SBAGLIATO: $startMonth = $currentMonth->subMonths(5); // Modifica $currentMonth!
        // GIUSTO:   $startMonth = $currentMonth->copy()->subMonths(5); // Lascia $currentMonth intatto
        $startMonth = $currentMonth->copy()->subMonths(5); // Es: 2025-02-01

        //dd($currentMonth, $startMonth);
        // Esempio pratico:
        // $currentMonth = 2025-07-01 (luglio)
        // $startMonth = 2025-02-01 (febbraio) perché luglio - 5 mesi = febbraio
        
        // Log::info('=== DEBUG MONTHLY REVENUE ===');
        // Log::info('Current Month: ' . $currentMonth->format('Y-m-d'));
        // Log::info('Start Month: ' . $startMonth->format('Y-m-d'));
        
        // 3. QUERY AL DATABASE
        $revenueData = Invoice::query()
            ->selectRaw('SUM(total) as total_amount, DATE_FORMAT(issue_date, "%Y-%m") as month_year')
            ->where('status', 'paid')
            ->where('issue_date', '>=', $startMonth)
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc')
            ->pluck('total_amount', 'month_year');
            // Risultato: ["2025-02" => 361.07] (se hai quella fattura di febbraio)
            //Log::info('Revenue Data from DB:', $revenueData->toArray());
            //dd($currentMonth, $startMonth,$revenueData);
            

        // 4. PREPARARE GLI ARRAY PER IL GRAFICO
        $labels = []; // Conterrà: ['Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug']
        $data = []; // Conterrà: [361.07, 0, 0, 0, 0, 0]

        // 5. CREARE UNA COLLECTION VUOTA
        // collect() = crea una "Collection" (come un array potenziato di Laravel)
        // È come un array normale ma con più funzioni utili (push, map, filter, ecc.)
        $monthsToProcess = collect(); // Inizialmente vuoto: []
        

        // 6. GENERARE I 6 MESI DA MOSTRARE
        for ($i = 0; $i < 6; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            // Partiamo dal mese di inizio (febbraio) e aggiungiamo mesi uno per volta
            
            // $startMonth->copy() = copia di febbraio 2025
            // ->addMonths($i) = aggiungi $i mesi
            
            // Iterazione 0: febbraio + 0 mesi = febbraio 2025
            // Iterazione 1: febbraio + 1 mese  = marzo 2025
            // Iterazione 2: febbraio + 2 mesi = aprile 2025
            // Iterazione 3: febbraio + 3 mesi = maggio 2025
            // Iterazione 4: febbraio + 4 mesi = giugno 2025
            // Iterazione 5: febbraio + 5 mesi = luglio 2025


            // push() = aggiungi alla collection (come array_push per gli array)
            $monthsToProcess->push($month);
            //Log::info("Mese {$i}: " . $month->format('Y-m-d') . ' -> ' . $month->translatedFormat('M'));
        }

        //dd($currentMonth, $startMonth,$revenueData,$monthsToProcess);
        
        // Ora $monthsToProcess contiene 6 oggetti Carbon (date):
        // [febbraio2025, marzo2025, aprile2025, maggio2025, giugno2025, luglio2025]


        // 7. ELABORARE OGNI MESE
        foreach ($monthsToProcess as $month) {
            // Per ogni mese nella collection...
        
            // translatedFormat('M') = nome del mese abbreviato in italiano
            // febbraio -> 'Feb', marzo -> 'Mar', ecc.
            $monthLabel = $month->translatedFormat('M');
            
             // format('Y-m') = formato anno-mese per cercare nel database
            // febbraio 2025 -> '2025-02'
            $monthKey = $month->format('Y-m');

            // Cercare il valore nel database
            // $revenueData->get('2025-02', 0) = cerca la chiave '2025-02'
            // Se la trova, restituisce il valore (361.07)
            // Se non la trova, restituisce 0 (valore di default)
            $monthValue = floatval($revenueData->get($monthKey, 0));
            
            // Aggiungere ai nostri array per il grafico
            $labels[] = $monthLabel; // ['Feb', 'Mar', ...]
            $data[] = $monthValue; // [361.07, 0, ...]
            
            //Log::info("Processing -> Key: {$monthKey} -> Label: {$monthLabel} -> Value: {$monthValue}");
        }
        //dd($currentMonth, $startMonth,$revenueData,$monthsToProcess,$labels,$data);



        //Log::info('Final Labels:', $labels);
        //Log::info('Final Data:', $data);

        // 8. RISULTATO FINALE
        return [
            'labels' => $labels, // ['Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug']
            'data' => $data // [361.07, 0, 0, 0, 0, 0]
        ];
    }

    public function render()
    {
        $totalRevenue = $this->get_total_revenue();
        $outstandingAmount = $this->get_outstanding_amount();
        $activeClientsCount = $this->get_active_clients_counts();
        $dueInvoicesCount = $this->get_upcoming_due_invoices_count();
        
        $latestInvoices = $this->get_latest_invoices();
        $latestClients = $this->get_recently_added_clients();
        $chart_data = $this->get_monthly_revenue();

        $chart_data['dataset_label'] = __('Monthly Revenue'); 
        $this->dispatch('updateChart', data: $chart_data);

        return view('livewire.dashboard.dashboard', compact(
            'totalRevenue',
            'outstandingAmount',
            'activeClientsCount',
            'dueInvoicesCount',
            'latestInvoices',
            'latestClients',
        ))->layout('layouts.app');
    }
}
