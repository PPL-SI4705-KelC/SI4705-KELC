<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing quizzes to avoid duplicates if run multiple times
        DB::table('quizzes')->truncate();

        $questions = [
            // Kategori: Transportasi (transport)
            [
                'question' => 'Mode transportasi manakah yang paling sedikit menghasilkan emisi karbon per penumpang?',
                'options' => ['Pesawat terbang', 'Mobil pribadi', 'Kereta listrik (KRL)', 'Bus bermesin diesel'],
                'correct_answer' => 2, // C. Kereta listrik (KRL)
                'category' => 'transport',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Mengapa menggunakan kendaraan umum (seperti bus atau kereta) lebih baik untuk lingkungan?',
                'options' => ['Karena tiketnya lebih murah', 'Mengurangi jumlah kendaraan di jalan raya sehingga menurunkan emisi total', 'Karena lebih cepat sampai tujuan', 'Karena kendaraannya lebih besar'],
                'correct_answer' => 1, // B.
                'category' => 'transport',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Apa dampak positif utama dari beralih menggunakan sepeda atau berjalan kaki untuk jarak dekat?',
                'options' => ['Menghasilkan emisi karbon nol (zero emission)', 'Meningkatkan konsumsi bahan bakar fosil', 'Membutuhkan biaya perawatan jalan yang mahal', 'Meningkatkan polusi suara'],
                'correct_answer' => 0, // A.
                'category' => 'transport',
                'difficulty' => 'easy',
            ],

            // Kategori: Makanan (consumption)
            [
                'question' => 'Bagaimana cara terbaik mengurangi "food waste" atau sampah makanan di rumah?',
                'options' => ['Membeli makanan dalam jumlah besar sekaligus', 'Merencanakan menu makan dan membeli bahan sesuai kebutuhan', 'Selalu menyisakan makanan di piring', 'Membuang makanan sisa ke tempat sampah tanpa memilahnya'],
                'correct_answer' => 1, // B.
                'category' => 'consumption',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Mengonsumsi bahan makanan lokal dan sesuai musim lebih ramah lingkungan karena?',
                'options' => ['Lebih bergengsi', 'Rasanya lebih enak', 'Mengurangi jejak karbon dari transportasi distribusi makanan jarak jauh', 'Kemasan makanannya lebih mewah'],
                'correct_answer' => 2, // C.
                'category' => 'consumption',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Praktik pertanian yang menghindari penggunaan pestisida kimia sintetis dan pupuk buatan disebut?',
                'options' => ['Pertanian konvensional', 'Pertanian monokultur', 'Pertanian organik', 'Pertanian hidroponik'],
                'correct_answer' => 2, // C.
                'category' => 'consumption',
                'difficulty' => 'medium',
            ],

            // Kategori: Energi (energy)
            [
                'question' => 'Manakah di bawah ini yang merupakan sumber energi terbarukan (renewable energy)?',
                'options' => ['Batu bara', 'Gas alam', 'Energi surya (matahari)', 'Minyak bumi'],
                'correct_answer' => 2, // C.
                'category' => 'energy',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Langkah sederhana apa yang bisa dilakukan di rumah untuk menghemat energi listrik?',
                'options' => ['Membiarkan lampu menyala pada siang hari', 'Mematikan peralatan elektronik dan mencabut kabelnya saat tidak digunakan', 'Menggunakan AC dengan suhu paling rendah terus menerus', 'Membuka pintu kulkas terlalu lama'],
                'correct_answer' => 1, // B.
                'category' => 'energy',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Apa manfaat utama mengganti lampu pijar biasa dengan lampu LED?',
                'options' => ['Lampu LED lebih panas', 'Lampu LED menggunakan energi jauh lebih sedikit dan tahan lebih lama', 'Lampu LED lebih berat', 'Lampu LED membutuhkan perawatan yang sering'],
                'correct_answer' => 1, // B.
                'category' => 'energy',
                'difficulty' => 'easy',
            ],
        ];

        foreach ($questions as $q) {
            Quiz::create($q);
        }
    }
}
