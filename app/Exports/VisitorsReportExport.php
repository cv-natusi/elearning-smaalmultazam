<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class VisitorsReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $kategori;
    protected $data;

    public function __construct(string $kategori)
    {
        $this->kategori = $kategori;
        // Set locale Carbon ke Bahasa Indonesia
        Carbon::setLocale('id');
    }

    /**
     * Query dasar untuk mendapatkan total harian.
     */
    private function getBaseQuery()
    {
        // 1. Subquery: Dapatkan IP unik per hari (ditandai sbg user/guest)
        $subQuery = DB::table('visitors')
            ->selectRaw("
                DATE(created_at) as date,
                ip_address,
                MAX(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as is_user
            ")
            ->groupBy('date', 'ip_address');

        // 2. Query Utama: Hitung total guest dan user per hari
        return DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery)
            ->selectRaw("
                sub.date,
                SUM(CASE WHEN sub.is_user = 0 THEN 1 ELSE 0 END) as guest,
                SUM(CASE WHEN sub.is_user = 1 THEN 1 ELSE 0 END) as user
            ")
            ->groupBy('sub.date');
    }

    /**
     * Mengambil data berdasarkan kategori.
     */
    public function collection()
    {
        $baseQuery = $this->getBaseQuery();

        if ($this->kategori === 'harian') {
            // Ambil data 1 bulan (30 hari) terakhir
            $this->data = $baseQuery
                ->where('sub.date', '>=', now()->subDays(30))
                ->orderBy('sub.date', 'asc')
                ->get();
        } 
        elseif ($this->kategori === 'bulanan') {
            // Agregat data harian menjadi bulanan untuk 12 bulan terakhir
            $dailyTotals = $baseQuery->where('sub.date', '>=', now()->subMonths(12));
            
            $this->data = DB::table(DB::raw("({$dailyTotals->toSql()}) as daily"))
                ->mergeBindings($dailyTotals)
                ->selectRaw("
                    YEAR(date) as tahun, 
                    MONTH(date) as bulan_angka, 
                    SUM(guest) as guest, 
                    SUM(user) as user
                ")
                ->groupBy('tahun', 'bulan_angka')
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan_angka', 'asc')
                ->get();
        } 
        elseif ($this->kategori === 'tahunan') {
            // Agregat data harian menjadi tahunan untuk 5 tahun terakhir
            $dailyTotals = $baseQuery->where('sub.date', '>=', now()->subYears(5));

            $this->data = DB::table(DB::raw("({$dailyTotals->toSql()}) as daily"))
                ->mergeBindings($dailyTotals)
                ->selectRaw("
                    YEAR(date) as tahun, 
                    SUM(guest) as guest, 
                    SUM(user) as user
                ")
                ->groupBy('tahun')
                ->orderBy('tahun', 'asc')
                ->get();
        }

        return $this->data;
    }

    /**
     * Menentukan judul kolom (header) Excel.
     */
    public function headings(): array
    {
        if ($this->kategori === 'harian') {
            return ['Tanggal', 'Hari', 'Guest', 'User'];
        }
        if ($this->kategori === 'bulanan') {
            return ['Bulan', 'Tahun', 'Guest', 'User'];
        }
        if ($this->kategori === 'tahunan') {
            return ['Tahun', 'Guest', 'User'];
        }
        return [];
    }

    /**
     * Memetakan data ke format yang diinginkan.
     */
    public function map($row): array
    {
        if ($this->kategori === 'harian') {
            $date = Carbon::parse($row->date);
            return [
                $date->format('j-M-Y'),       // 1-Nov-2025
                $date->translatedFormat('l'), // Sabtu
                $row->guest,
                $row->user,
            ];
        }
        if ($this->kategori === 'bulanan') {
            return [
                Carbon::create()->month($row->bulan_angka)->translatedFormat('F'), // Januari
                $row->tahun,
                $row->guest,
                $row->user,
            ];
        }
        if ($this->kategori === 'tahunan') {
            return [
                $row->tahun,
                $row->guest,
                $row->user,
            ];
        }
        return [];
    }
}