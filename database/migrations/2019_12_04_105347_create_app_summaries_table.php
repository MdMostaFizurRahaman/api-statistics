<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAppSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable()->index('app_date_index');
            $table->string('country')->nullable()->index('app_country_index');
            $table->string('app_name')->nullable()->index('app_name_index');
            $table->string('app_version')->nullable()->index('app_version_index');
            $table->string('total')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_summaries');
    }
}