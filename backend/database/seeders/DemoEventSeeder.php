<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\EventCategory;
use App\Models\Venue;
use App\Models\TicketType;
use App\Models\Speaker;
use App\Models\EventSession;
use App\Models\EventPromotion;
use App\Models\EventPromotionPlan;
use Illuminate\Support\Str;

class DemoEventSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('email', 'organizer@smartevent.com')->first();
        if (!$organizer) {
            $organizer = User::first();
        }

        $venues = Venue::all();
        if ($venues->isEmpty()) return;
        
        $categories = EventCategory::all();
        if ($categories->isEmpty()) return;

        $eventList = [
            [
                'title' => 'Ecosystem Symphony & Green Horizon 2026',
                'category_slug' => 'technology',
                'short_description' => 'A symphonic gathering exploring sustainable development, micro-climates, and green architecture.',
                'description' => "Our shared environment is the ultimate architecture. Ecosystem Symphony brings together urban planners, environmental researchers, and digital builders to discuss future green horizons. Learn how localized technological solutions can integrate with wild ecological systems.\n\nEnjoy panels on micro-climate architecture, interactive design systems, and regenerative spatial mapping.",
                'type' => 'physical',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 1500,
                'registered_count' => 120,
                'is_featured' => true,
                'views_count' => 840,
                'promoted' => true,
                'tickets' => [
                    ['name' => 'Standard Resonance', 'type' => 'regular', 'price' => 150.00, 'original_price' => 200.00],
                    ['name' => 'VIP Biosphere Pass', 'type' => 'vip', 'price' => 450.00, 'original_price' => 600.00],
                ],
                'speakers' => [
                    ['name' => 'Dr. Elena Thorne', 'designation' => 'Ecological Designer', 'bio' => 'Elena Thorne focuses on the intersection of carbon sequestration structures and smart urbanism.'],
                ]
            ],
            [
                'title' => 'Global Eco-Tech Alliance Summit',
                'category_slug' => 'business',
                'short_description' => 'Uniting global business visionaries to build sustainable industrial infrastructure.',
                'description' => "Uniting global industrial nodes to define the business paradigms of tomorrow. Discover how next-gen economic frameworks scale alongside environmental protocols. This summit features three keynotes and two design sprints focused on corporate responsibility and eco-friendly capital allocation.\n\nInteract with investment leads, founders, and compliance leads from over twenty countries.",
                'type' => 'physical',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 1000,
                'registered_count' => 400,
                'is_featured' => true,
                'views_count' => 1100,
                'promoted' => true,
                'tickets' => [
                    ['name' => 'Corporate Delegate', 'type' => 'regular', 'price' => 350.00, 'original_price' => 400.00],
                    ['name' => 'Alliance VIP', 'type' => 'vip', 'price' => 790.00, 'original_price' => 1000.00],
                ],
                'speakers' => [
                    ['name' => 'Marcus Vance', 'designation' => 'Principal Investor', 'bio' => 'Marcus leads circular economy investments at Grounded Capital.'],
                ]
            ],
            [
                'title' => 'Global Tech Summit 2026',
                'category_slug' => 'technology',
                'short_description' => 'The biggest tech conference of the year covering AI, networks, and ecosystem design.',
                'description' => "Explore the next frontier of human-machine orchestration. The Global Tech Summit features tracks on distributed systems, neural networks, and interactive interface design.\n\nCollaborate with developers, product managers, and UI/UX designers in an atmospheric, collaborative setting designed to seed connections.",
                'type' => 'physical',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 2000,
                'registered_count' => 150,
                'is_featured' => true,
                'views_count' => 1250,
                'promoted' => true,
                'tickets' => [
                    ['name' => 'General Pass', 'type' => 'regular', 'price' => 99.00, 'original_price' => 150.00],
                    ['name' => 'Full Access VIP', 'type' => 'vip', 'price' => 299.00, 'original_price' => 400.00],
                ],
                'speakers' => [
                    ['name' => 'Aria Chen', 'designation' => 'Lead AI Architect', 'bio' => 'Aria works on custom machine learning pipelines and distributed LLM frameworks.'],
                ]
            ],
            [
                'title' => 'AI Workshop: Practical LLMs',
                'category_slug' => 'technology',
                'short_description' => 'Hands-on practical development workshop building web apps and fine-tuning models.',
                'description' => "Get your hands dirty with state-of-the-art developer tooling. Learn how to construct, prompt-optimize, and deploy localized models using Docker, Node, and Laravel integration layers.\n\nWe provide fully working models for testing during the workshop. Bring your laptops and curiosity.",
                'type' => 'online',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 100,
                'registered_count' => 60,
                'is_featured' => false,
                'views_count' => 310,
                'tickets' => [
                    ['name' => 'Developer Ticket', 'type' => 'regular', 'price' => 50.00, 'original_price' => 80.00],
                    ['name' => 'VIP Interactive Seat', 'type' => 'vip', 'price' => 120.00, 'original_price' => 150.00],
                ],
                'speakers' => [
                    ['name' => 'Liam Sterling', 'designation' => 'DevOps Specialist', 'bio' => 'Liam is a solutions engineer focusing on Dockerized orchestration patterns.'],
                ]
            ],
            [
                'title' => 'Creative Design Futures 2026',
                'category_slug' => 'design',
                'short_description' => 'Exploring glassmorphism, responsive micro-animations, and future-forward design patterns.',
                'description' => "Design is not just what it looks like; it is how it feels. Connect with design thinkers pushing the boundaries of typography, layouts, motion graphics, and visual hierarchy.\n\nFeaturing immersive galleries, portfolio critiques, and interactive feedback loops.",
                'type' => 'physical',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 800,
                'registered_count' => 320,
                'is_featured' => false,
                'views_count' => 640,
                'tickets' => [
                    ['name' => 'Creative Pass', 'type' => 'regular', 'price' => 120.00, 'original_price' => 150.00],
                    ['name' => 'Studio Masterclass Pass', 'type' => 'vip', 'price' => 380.00, 'original_price' => 500.00],
                ],
                'speakers' => [
                    ['name' => 'Seraphina Vance', 'designation' => 'Visual Director', 'bio' => 'Seraphina designs interactive design systems for premium brands.'],
                ]
            ],
            [
                'title' => 'Starlight Rhythm & Harmony Festival',
                'category_slug' => 'music',
                'short_description' => 'A premium ambient and acoustic festival connecting musical nodes in Mumbai.',
                'description' => "Enjoy a night of acoustic resonance, ambient synthesis, and starlit melodies. This festival gathers top indie musicians and sound engineers in a beautifully curated garden space.\n\nCalibrate your auditory senses under the open sky.",
                'type' => 'physical',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 1200,
                'registered_count' => 850,
                'is_featured' => true,
                'views_count' => 980,
                'tickets' => [
                    ['name' => 'Lawn Access Entry', 'type' => 'regular', 'price' => 75.00, 'original_price' => 100.00],
                    ['name' => 'Front row acoustics seat', 'type' => 'vip', 'price' => 220.00, 'original_price' => 300.00],
                ],
                'speakers' => [
                    ['name' => 'Kai Alvarez', 'designation' => 'Sonic Alchemist', 'bio' => 'Kai designs acoustic environments and synthesizes bio-mechanical frequencies.'],
                ]
            ],
            [
                'title' => 'Econ-Socio Innovation Forum',
                'category_slug' => 'business',
                'short_description' => 'Discussing community capital networks, blockchain ledgers, and resource governance.',
                'description' => "How do we scale community capital without losing alignment? Join local and global economists, policy-makers, and governance builders in a series of roundtable panels and collaborative design tasks.",
                'type' => 'physical',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 600,
                'registered_count' => 150,
                'is_featured' => false,
                'views_count' => 280,
                'tickets' => [
                    ['name' => 'Standard Delegate Pass', 'type' => 'regular', 'price' => 110.00, 'original_price' => 130.00],
                    ['name' => 'Executive Innovation Board Pass', 'type' => 'vip', 'price' => 320.00, 'original_price' => 400.00],
                ],
                'speakers' => [
                    ['name' => 'Dr. Julian Sterling', 'designation' => 'Behavioral Economist', 'bio' => 'Julian writes on decentralized capital networks and community governance architectures.'],
                ]
            ],
            [
                'title' => 'Ultimate Fitness & Athletic Challenge',
                'category_slug' => 'sports',
                'short_description' => 'An active outdoor team challenge designed to test strength, agility, and strategy.',
                'description' => "Bring your team and compete in our physical obstacle courses, agility sprint modules, and strategic coordination loops. Build resilience and form lasting connections through teamwork.",
                'type' => 'physical',
                'status' => 'published',
                'is_restricted' => false,
                'total_capacity' => 400,
                'registered_count' => 380,
                'is_featured' => false,
                'views_count' => 480,
                'tickets' => [
                    ['name' => 'Standard Challenger Seat', 'type' => 'regular', 'price' => 45.00, 'original_price' => 60.00],
                    ['name' => 'All-inclusive athlete pass', 'type' => 'vip', 'price' => 140.00, 'original_price' => 180.00],
                ],
                'speakers' => [
                    ['name' => 'Coach Evelyn Vance', 'designation' => 'Human Performance Lead', 'bio' => 'Evelyn coaches olympic-class athletes on biometric optimization and spatial strategy.'],
                ]
            ],
        ];

        foreach ($eventList as $eventData) {
            $category = $categories->firstWhere('slug', $eventData['category_slug']);
            if (!$category) {
                $category = $categories->first();
            }

            // Distribute venues
            $venueIndex = rand(0, $venues->count() - 1);
            $venue = $venues[$venueIndex];

            // Create or update event
            $event = Event::updateOrCreate(
                ['slug' => Str::slug($eventData['title'])],
                [
                    'organizer_id' => $organizer->id,
                    'category_id' => $category->id,
                    'venue_id' => $venue->id,
                    'title' => $eventData['title'],
                    'short_description' => $eventData['short_description'],
                    'description' => $eventData['description'],
                    'type' => $eventData['type'],
                    'status' => $eventData['status'],
                    'is_restricted' => $eventData['is_restricted'],
                    'restriction_reason' => null,
                    'start_date' => now()->addDays(rand(10, 60))->setHour(10)->setMinute(0),
                    'end_date' => now()->addDays(rand(10, 60))->setHour(18)->setMinute(0),
                    'total_capacity' => $eventData['total_capacity'],
                    'registered_count' => $eventData['registered_count'],
                    'is_featured' => $eventData['is_featured'],
                    'views_count' => $eventData['views_count'],
                ]
            );

            // Create tickets
            foreach ($eventData['tickets'] as $ticketData) {
                TicketType::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'name' => $ticketData['name']
                    ],
                    [
                        'type' => $ticketData['type'],
                        'price' => $ticketData['price'],
                        'original_price' => $ticketData['original_price'],
                        'quantity_total' => 200,
                        'quantity_sold' => 0,
                        'max_per_order' => 10,
                        'min_per_order' => 1,
                        'is_active' => true,
                    ]
                );
            }

            // Create speakers and sessions
            foreach ($eventData['speakers'] as $speakerData) {
                $speaker = Speaker::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'name' => $speakerData['name']
                    ],
                    [
                        'designation' => $speakerData['designation'],
                        'bio' => $speakerData['bio'],
                    ]
                );

                // Create a matching session
                EventSession::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'title' => 'Keynote: ' . $eventData['title']
                    ],
                    [
                        'speaker_id' => $speaker->id,
                        'description' => 'Opening session exploring structural frameworks and future opportunities.',
                        'start_time' => $event->start_date->setHour(10)->setMinute(30),
                        'end_time' => $event->start_date->setHour(12)->setMinute(00),
                        'room_or_track' => 'Auditorium Alpha',
                        'capacity' => 150,
                    ]
                );
            }

            // If the event should be promoted, seed a promotion
            if (isset($eventData['promoted']) && $eventData['promoted']) {
                $plan = EventPromotionPlan::first();
                if ($plan) {
                    EventPromotion::updateOrCreate(
                        [
                            'event_id' => $event->id,
                            'plan_id' => $plan->id
                        ],
                        [
                            'amount_paid' => $plan->price,
                            'payment_status' => 'paid',
                            'status' => 'approved',
                            'start_date' => now()->subDays(1),
                            'end_date' => now()->addDays(14),
                        ]
                    );
                }
            }
        }
    }
}
