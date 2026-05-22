<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Ky_thi', function (Blueprint $table) {
            // 1 = xem điểm + đáp án + bài làm; 2 = xem điểm + bài làm; 3 = không được xem
            $table->tinyInteger('CheDo_XemKetQua_KyThi')->default(1)->after('ID_MaDeThi');
        });
    }

    public function down(): void
    {
        Schema::table('Ky_thi', function (Blueprint $table) {
            $table->dropColumn('CheDo_XemKetQua_KyThi');
        });
    }
};
