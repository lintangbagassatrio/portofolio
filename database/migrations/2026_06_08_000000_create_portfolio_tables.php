<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Profiles table
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('birth_place_date')->nullable();
            $table->string('education')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('cv_file')->nullable();
            $table->text('background')->nullable();
            $table->text('career')->nullable();
            $table->text('interests')->nullable();
            $table->text('goals')->nullable();
            $table->timestamps();
        });

        // 2. Skills table
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // 'technical' or 'soft'
            $table->integer('level'); // percentage 1-100
            $table->timestamps();
        });

        // 3. Portfolio Categories table
        Schema::create('portfolio_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 4. Portfolios table
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('portfolio_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->json('technology_used')->nullable(); // stored as JSON array
            $table->date('start_date')->nullable();
            $table->string('status')->default('completed'); // 'completed' or 'in-progress'
            $table->string('demo_link')->nullable();
            $table->string('github_link')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // 5. Experiences table
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company');
            $table->string('type'); // 'work', 'internship', 'freelance', 'organization'
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 6. Certificates table
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('publisher');
            $table->integer('year');
            $table->string('thumbnail')->nullable();
            $table->string('file_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 7. Blog Categories table
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 8. Blogs table
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->longText('content')->nullable();
            $table->json('tags')->nullable(); // stored as JSON array
            $table->string('status')->default('draft'); // 'draft' or 'published'
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        // 9. Blog Comments table
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->text('comment');
            $table->boolean('is_approved')->default(true); // default true for easy demo, but modifiable
            $table->timestamps();
        });

        // 10. Contacts table
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 11. Visitors table
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->date('visit_date');
            $table->string('page_visited');
            $table->timestamps();
        });

        // 12. Settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // 'text', 'image', 'textarea'
            $table->timestamps();
        });

        // 13. Activity Logs table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('portfolio_categories');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('profiles');
    }
};
