<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableName = config('easy-audits.audit_table_name');

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned();
                $table->string('ip', 40);
                $table->string('event_type', 12);
                $table->string('table', 50);
                $table->json('old_values')->nullable();
                $table->json('new_values');
                $table->timestamps();

                $table->index(['user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(config('easy-audits.audit_table_name'));
    }
};
