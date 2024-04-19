<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barua_templates', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('mailable');
            $table->text('subject');
            $table->longText('html_template');
            $table->longText('text_template')->nullable();
            $table->longText('template_file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function down()
    {
        Schema::dropIfExists('barua_templates');
    }
};
