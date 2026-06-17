<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
public function up(): void
{
    if (DB::getDriverName() !== 'sqlite') {

        DB::table('pagos')->update([
            'monto' => DB::raw("REPLACE(REPLACE(monto, ',', ''), ' ', '')")
        ]);

        DB::table('pagos')
            ->whereNull('monto')
            ->orWhere('monto', '')
            ->orWhereRaw("monto NOT REGEXP '^[0-9]+(\\.[0-9]{1,2})?$'")
            ->update([
                'monto' => 0
            ]);

        DB::table('pagos')
            ->whereNotNull('fecha_pago')
            ->update([
                'fecha_pago' => DB::raw("
                    CASE
                        WHEN fecha_pago REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}'
                        THEN DATE(fecha_pago)
                        ELSE NULL
                    END
                ")
            ]);

        DB::table('pagos')
            ->whereRaw("fecha_pago NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'")
            ->update([
                'fecha_pago' => null
            ]);
    }

    Schema::table('pagos', function (Blueprint $table) {
        $table->decimal('monto', 10, 2)->change();
        $table->date('fecha_pago')->change();
        $table->string('matricula')->change();
        $table->string('estado', 30)->default('activo')->change();
    });
}

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->text('monto')->change();
            $table->text('fecha_pago')->change();
            $table->text('matricula')->change();
            $table->integer('sede_id')->nullable()->change();
            $table->text('estado')->change();
        });
    }
};