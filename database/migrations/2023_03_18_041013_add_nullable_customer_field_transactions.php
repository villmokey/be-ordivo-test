<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('customer_email')->nullable()->change();
            $table->string('customer_address')->nullable()->change();
            $table->string('customer_phone', 15)->nullable()->after('customer_name');
            $table->decimal('tax')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('customer_phone');
            $table->dropColumn('tax');
        });
    }
};
