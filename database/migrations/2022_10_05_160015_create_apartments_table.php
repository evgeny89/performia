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
    public function up(): void
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("price")->unsigned();
            $table->integer("bedrooms")->unsigned();
            $table->integer("bathrooms")->unsigned();
            $table->integer("storeys")->unsigned();
            $table->integer("garages")->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
