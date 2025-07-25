<?php

namespace App\Livewire\Dashboard;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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


        $revenueData = Invoice::query()->select([
            DB::raw('sum(total) as total_amount'),
            DB::raw('DATE_FORMAT(issue_date, "%Y-%m") as month_year')
        ])
            ->where('status', 'paid')
            ->where('issue_date', '>=', now()->subMonths(6))
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc')
            ->get()
            ->pluck('total_amount','month_year');
        // $revenueData = ["2025-02" => 361.07]; 
        $labels = []; // Conterrà le etichette, es: ['Feb', 'Mar', 'Apr', ...]
        $data = []; // Conterrà i dati, es: [361.07, 0, 0, ...]

        //Ciclo for che va dagli ultimi 5 mesi a oggi (6 mesi in totale)
        for ($i = 5; $i >= 0;$i--){
            $month = now()->subMonths($i); // now=luglio --> now()-->subMonths(5) --> 7-5=2 -->Febbraio 2025

            //Formattiamo il mese per l'etichetta del grafico (es. "Lug")
            $labels[] = $month->translatedFormat('M'); // 2->Feb

            //Formattiamo il mese per cercare nei nostri dati(es "2025-07)
            $month_key = $month->format('Y-m'); // 2025-02

            //Riempiamo l'array data con la corrispondenza dei dati, se non c'è corrispondenza mettiamo 0
            $data[] = $revenueData->get($month_key, 0); //Cerchiamo la chiave 2025-02 e se la troviamo aggiungiamo il valore a $data

            /*
            Quindi avremo
            $labels = ['Feb'] e $data = [361,07]
            Completando il ciclo avremo questo risultato finale
            $labels = ['Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug'];
            $data   = [361.07, 0, 0, 0, 0, 0];
            */
        }

        return ['labels'=>$labels, 'data'=>$data];
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
