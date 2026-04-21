$mobils = \App\Models\Mobil::with('branch')->where('status', 'tersedia')->get();
$output = [];
foreach($mobils as $m) {
    if ($m->branch) {
        $output[] = "Rental ID: {$m->rental_id} | Kota: {$m->branch->kota} | Mobil: {$m->merk}";
    }
}
echo implode("\n", $output);
