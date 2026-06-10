<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\PortfolioCategory;
use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Certificate;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin Portfolio',
            'email' => 'admin@portfolio.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $editor = User::create([
            'name' => 'Editor Portfolio',
            'email' => 'editor@portfolio.com',
            'password' => Hash::make('password123'),
            'role' => 'editor',
        ]);

        // 2. Seed Profile for Admin
        Profile::create([
            'user_id' => $admin->id,
            'full_name' => 'John Doe',
            'birth_place_date' => 'Jakarta, 15 May 1999',
            'education' => 'Bachelor of Computer Science, Indonesia University',
            'address' => 'Jakarta, Indonesia',
            'email' => 'john.doe@portfolio.com',
            'phone' => '+62 812-3456-7890',
            'bio' => 'I am a passionate software engineer and web developer with expertise in building responsive, modern web applications using Laravel, MySQL, and modern JavaScript. I enjoy creating solutions that are clean, performant, and user-friendly.',
            'photo' => null,
            'cv_file' => null,
            'background' => 'I started my coding journey in 2018. Over the years, I have worked as a freelancer, intern, and full-time developer. I specialize in backend development with PHP/Laravel, but I also have strong frontend skills using HTML, CSS, JavaScript, and modern frameworks.',
            'career' => 'My career is driven by continuous learning and writing clean code. I have helped startups build scalable SaaS tools, designed user-centric landing pages, and optimized database queries to handle large workloads.',
            'interests' => 'Web development, database optimization, Clean Architecture, open-source projects, system design, technical writing, and UI/UX engineering.',
            'goals' => 'My main professional goal is to create high-impact software that makes life easier for users. I aim to grow as a tech lead and continue contributing to the developer community.',
        ]);

        // 3. Seed Settings
        Setting::set('site_name', 'John Doe Portfolio', 'text');
        Setting::set('site_logo', '', 'image');
        Setting::set('site_favicon', '', 'image');
        Setting::set('meta_title', 'John Doe - Software Engineer & Web Developer Portfolio', 'text');
        Setting::set('meta_description', 'Explore John Doe\'s personal portfolio. View web projects, technical skills, certifications, work experiences, and tech blog posts.', 'textarea');
        Setting::set('meta_keywords', 'portfolio, laravel, web developer, software engineer, full stack, PHP, javascript, clean code', 'text');
        Setting::set('social_whatsapp', '6281234567890', 'text');
        Setting::set('social_email', 'john.doe@portfolio.com', 'text');
        Setting::set('social_linkedin', 'https://linkedin.com', 'text');
        Setting::set('social_github', 'https://github.com', 'text');
        Setting::set('social_instagram', 'https://instagram.com', 'text');
        Setting::set('footer_text', '© 2026 John Doe. Built with Laravel & Passion.', 'text');
        Setting::set('google_analytics', '', 'textarea');

        // 4. Seed Skills
        $skills = [
            // Technical Skills
            ['name' => 'HTML', 'category' => 'technical', 'level' => 95],
            ['name' => 'CSS', 'category' => 'technical', 'level' => 90],
            ['name' => 'JavaScript', 'category' => 'technical', 'level' => 85],
            ['name' => 'PHP', 'category' => 'technical', 'level' => 90],
            ['name' => 'Laravel', 'category' => 'technical', 'level' => 88],
            ['name' => 'MySQL', 'category' => 'technical', 'level' => 82],
            ['name' => 'Git', 'category' => 'technical', 'level' => 85],
            ['name' => 'QA Testing', 'category' => 'technical', 'level' => 75],
            ['name' => 'System Analysis', 'category' => 'technical', 'level' => 80],
            // Soft Skills
            ['name' => 'Problem Solving', 'category' => 'soft', 'level' => 90],
            ['name' => 'Communication', 'category' => 'soft', 'level' => 85],
            ['name' => 'Teamwork', 'category' => 'soft', 'level' => 90],
            ['name' => 'Leadership', 'category' => 'soft', 'level' => 80],
            ['name' => 'Critical Thinking', 'category' => 'soft', 'level' => 85],
        ];
        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // 5. Seed Portfolio Categories
        $categories = [
            'Web Development' => 'web-development',
            'Mobile Development' => 'mobile-development',
            'UI/UX Design' => 'ui-ux-design',
            'Quality Assurance' => 'quality-assurance',
            'System Analysis' => 'system-analysis',
            'Data Analysis' => 'data-analysis',
        ];
        $catModels = [];
        foreach ($categories as $name => $slug) {
            $catModels[$slug] = PortfolioCategory::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        // 6. Seed Portfolios
        Portfolio::create([
            'category_id' => $catModels['web-development']->id,
            'title' => 'E-Commerce Management System',
            'slug' => 'e-commerce-management-system',
            'thumbnail' => null,
            'description' => 'A robust and scalable e-commerce backend and administrative panel. Features include stock management, automated invoice generation, transactional email notices, multi-payment gateway integrations, and comprehensive sales reports.',
            'technology_used' => ['Laravel', 'MySQL', 'AlpineJS', 'TailwindCSS'],
            'start_date' => '2025-01-10',
            'status' => 'completed',
            'demo_link' => 'https://demo.example.com/ecommerce',
            'github_link' => 'https://github.com/example/ecommerce',
            'is_featured' => true,
        ]);

        Portfolio::create([
            'category_id' => $catModels['web-development']->id,
            'title' => 'Corporate Attendance Portal',
            'slug' => 'corporate-attendance-portal',
            'thumbnail' => null,
            'description' => 'An online attendance registration portal for workers. Integrates geolocation validation to verify check-in places, generates monthly attendance reports in Excel and PDF format, and handles shift scheduling.',
            'technology_used' => ['Laravel', 'MySQL', 'Bootstrap', 'JavaScript'],
            'start_date' => '2024-08-15',
            'status' => 'completed',
            'demo_link' => 'https://demo.example.com/attendance',
            'github_link' => 'https://github.com/example/attendance',
            'is_featured' => true,
        ]);

        Portfolio::create([
            'category_id' => $catModels['ui-ux-design']->id,
            'title' => 'Financial Mobile App Design',
            'slug' => 'financial-mobile-app-design',
            'thumbnail' => null,
            'description' => 'A Figma high-fidelity prototype design for a financial tracking mobile app. Features an elegant dark mode dashboard, smooth micro-interactions, and visual representations of user monthly savings.',
            'technology_used' => ['Figma', 'Adobe Illustrator'],
            'start_date' => '2024-05-01',
            'status' => 'completed',
            'demo_link' => 'https://figma.com/file/example',
            'github_link' => null,
            'is_featured' => false,
        ]);

        // 7. Seed Experiences
        Experience::create([
            'title' => 'Full-Stack Web Developer',
            'company' => 'Innovation Tech Studio',
            'type' => 'work',
            'start_date' => '2024-01-15',
            'end_date' => null,
            'is_current' => true,
            'description' => 'Responsible for building and maintaining enterprise Laravel web applications, coordinating with backend devs and UI designers, optimizing database queries, and designing responsive customer interfaces.',
        ]);

        Experience::create([
            'title' => 'Junior Web Developer (Freelance)',
            'company' => 'Self-employed / Upwork',
            'type' => 'freelance',
            'start_date' => '2022-06-01',
            'end_date' => '2023-12-31',
            'is_current' => false,
            'description' => 'Completed multiple custom websites for small local businesses using PHP, MySQL, and Javascript. Handled full project cycle, including requirement gathers, UI wireframing, deployment, and testing.',
        ]);

        Experience::create([
            'title' => 'Quality Assurance Intern',
            'company' => 'Pixel Perfect Labs',
            'type' => 'internship',
            'start_date' => '2021-09-01',
            'end_date' => '2022-02-28',
            'is_current' => false,
            'description' => 'Performed manual testing and automated script execution on dynamic web portals. Logged bugs, verified error handling structures, and performed cross-browser compatibility tests.',
        ]);

        Experience::create([
            'title' => 'Head of Technology Division',
            'company' => 'University Computer Club',
            'type' => 'organization',
            'start_date' => '2020-03-01',
            'end_date' => '2021-06-30',
            'is_current' => false,
            'description' => 'Led a team of 15 members to plan and build the official university student activities portal. Organized workshops on Git version control and modern HTML/CSS design standards.',
        ]);

        // 8. Seed Certificates
        Certificate::create([
            'name' => 'Laravel Professional Certification',
            'publisher' => 'Laravel Institute',
            'year' => 2025,
            'thumbnail' => null,
            'file_path' => null,
            'description' => 'Official certification validating deep knowledge in MVC architecture, routing, migrations, ORM database design, auth mechanisms, and API developments in Laravel.',
        ]);

        Certificate::create([
            'name' => 'Professional Scrum Master I',
            'publisher' => 'Scrum.org',
            'year' => 2024,
            'thumbnail' => null,
            'file_path' => null,
            'description' => 'Validation of understanding regarding Scrum principles, Agile team setups, sprint iterations, product logs, and agile lifecycle strategies.',
        ]);

        // 9. Seed Blog Categories
        $blogCats = [
            'Programming' => 'programming',
            'Web Design' => 'web-design',
            'Tech Trends' => 'tech-trends',
        ];
        $blogCatModels = [];
        foreach ($blogCats as $name => $slug) {
            $blogCatModels[$slug] = BlogCategory::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        // 10. Seed Blogs
        Blog::create([
            'category_id' => $blogCatModels['programming']->id,
            'user_id' => $admin->id,
            'title' => 'How to Implement Clean Architecture in Laravel',
            'slug' => 'how-to-implement-clean-architecture-in-laravel',
            'thumbnail' => null,
            'content' => 'Clean Architecture is a software design philosophy that promotes separation of concerns and makes systems easy to maintain, test, and adapt over time. In this article, we explore how to structure your Laravel projects by decoupling core business rules from external frameworks, databases, and UI components. We will cover Repository patterns, Service layers, and Data Transfer Objects (DTOs) with code examples.',
            'tags' => ['laravel', 'php', 'clean-architecture', 'design-patterns'],
            'status' => 'published',
            'views' => 125,
        ]);

        Blog::create([
            'category_id' => $blogCatModels['web-design']->id,
            'user_id' => $admin->id,
            'title' => 'A Guide to Styling Web Apps with CSS Custom Variables',
            'slug' => 'guide-to-styling-web-apps-css-variables',
            'thumbnail' => null,
            'content' => 'CSS variables (Custom Properties) have transformed the way we write and maintain stylesheets. They provide dynamic, readable, and highly reusable design tokens right inside vanilla CSS. This guide explains how to set up color schemes, typography parameters, and dark/light toggles using native CSS variables. We will also see how to manipulate them in real-time using vanilla JavaScript.',
            'tags' => ['css', 'styling', 'dark-mode', 'vanilla-js'],
            'status' => 'published',
            'views' => 98,
        ]);

        Blog::create([
            'category_id' => $blogCatModels['tech-trends']->id,
            'user_id' => $editor->id,
            'title' => 'Why Web Accessibility Matters More Than Ever in 2026',
            'slug' => 'why-web-accessibility-matters-2026',
            'thumbnail' => null,
            'content' => 'Web Accessibility (a11y) is no longer a luxury or an afterthought; it is a fundamental pillar of modern web design. As web portals become the main touchpoint for services, we must ensure everyone—regardless of physical or cognitive capabilities—can navigate them easily. In this post, we discuss semantic HTML, screen-reader markup, ARIA roles, and color contrast ratios that make your portfolio truly universal.',
            'tags' => ['accessibility', 'html5', 'ux-design', 'standards'],
            'status' => 'published',
            'views' => 47,
        ]);
    }
}
