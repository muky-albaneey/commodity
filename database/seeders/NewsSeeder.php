<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $news = [
            [
                'tag' => 'Market Updates',
                'link' => 'https://nairametrics.com/2024/11/20/cocoa-futures-rise-16-in-november-surpassing-100-year-to-date-gains-amid-supply-shortages/',
                'title' => 'Cocoa Futures Rise 16% in November Surpassing 100% Year-to-Date Gains Amid Supply Shortages',
                'description' => 'Cocoa futures have surged by 16% in November, surpassing 100% year-to-date gains amid supply shortages. This development highlights the impact of global cocoa production challenges on the market.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/11/Cocoa-Futures-Rise-16-in-November-Surpassing-100-Year-to-Date-Gains-Amid-Supply-Shortages.jpg',
                'author' => 'Nairametrics',
                'readTime' => 3,
                'date' => '2024-11-20'
            ],
            [
                'tag' => 'Analysis',
                'link' => 'https://www.farmjournal.com/five-secrets-to-successful-influencer-partnerships-in-the-food-and-agriculture-space/',
                'title' => 'Five Secrets to Successful Influencer Partnerships in the Food and Agriculture Space',
                'description' => 'Influencer partnerships can be a powerful tool for brands in the food and agriculture sector to reach new audiences and drive sales. However, successful collaborations require careful planning and execution. Here are five secrets to help you navigate the process and achieve your goals.',
                'image' => 'https://www.farmjournal.com/wp-content/uploads/2024/11/Five-Secrets-to-Successful-Influencer-Partnerships-in-the-Food-and-Agriculture-Space.jpg',
                'author' => 'Farm Journal',
                'readTime' => 3,
                'date' => '2024-11-11'
            ],
            [
                'tag' => 'Market Updates',
                'link' => 'https://nairametrics.com/2024/11/12/gold-sheds-over-6-of-its-year-to-date-performance-after-trumps-win-slips-below-2700/',
                'title' => 'Gold Sheds Over 6% of Its Year-to-Date Performance After Trump\'s Win Slips Below $2,700',
                'description' => 'Gold prices have dropped by over 6% in the past week, slipping below $2,700 after former US President Donald Trump\'s victory in the 2024 US presidential election. This marks a significant decline in the precious metal\'s performance this year.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/11/Gold-Sheds-Over-6-of-Its-Year-to-Date-Performance-After-Trump-s-Win-Slips-Below-2700.jpg',
                'author' => 'Nairametrics',
                'readTime' => 5,
                'date' => '2024-11-10'
            ],
            [
                'tag' => 'Market Updates',
                'link' => 'https://nairametrics.com/2024/11/01/cocoa-production-in-nigeria-projected-to-rise-by-10-over-high-prices-report/',
                'title' => 'Cocoa Production in Nigeria Projected to Rise by 10% Over High Prices Report',
                'description' => 'Nigeria\'s cocoa production is expected to increase by 10% this season, driven by high prices and improved yields. This growth is a positive sign for the cocoa industry in Nigeria, as it aims to boost local production and reduce reliance on imports.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/11/Cocoa-Production-in-Nigeria-Projected-to-Rise-by-10-Over-High-Prices-Report.jpg',
                'author' => 'Nairametrics',
                'readTime' => 7,
                'date' => '2024-11-01'
            ],
            [
                'tag' => 'Company News',
                'link' => 'https://www.farmjournal.com/three-million-monthly-video-views-advertising-lessons-learned-from-farm-journals-youtube-shorts-facebook-reels-and-tiktok-videos/',
                'title' => 'Three Million Monthly Video Views: Advertising Lessons Learned from Farm Journals\' YouTube Shorts, Facebook Reels, and TikTok Videos',
                'description' => 'Farm Journal has achieved three million monthly video views across its YouTube Shorts, Facebook Reels, and TikTok videos. This milestone reflects the publication\'s success in leveraging short-form video content to engage and inform its audience.',
                'image' => 'https://www.farmjournal.com/wp-content/uploads/2024/11/Three-Million-Monthly-Video-Views-Advertising-Lessons-Learned-from-Farm-Journals-YouTube-Shorts-Facebook-Reels-and-TikTok-Videos.jpg',
                'author' => 'Farm Journal',
                'readTime' => 3,
                'date' => '2024-10-29'
            ],
            [
                'tag' => 'Analysis',
                'link' => 'https://www.farmjournal.com/2024-crop-life-america-annual-meeting-highlights/',
                'title' => '2024 Crop Life America Annual Meeting Highlights',
                'description' => 'The 2024 Crop Life America Annual Meeting provided a platform for industry leaders to discuss the latest advancements in crop protection technology and sustainability. Key topics included the integration of precision agriculture solutions and the development of new, environmentally friendly pest control methods.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/04/Cocoa-Price-750x375.jpg',
                'author' => 'Nairametrics',
                'readTime' => 3,
                'date' => '2024-10-22'
            ],
            [
                'tag' => 'Company News',
                'link' => 'https://www.farmjournal.com/how-shark-week-inspired-a-successful-omnichannel-campaign/',
                'title' => 'How Shark Week Inspired a Successful Omnichannel Campaign',
                'description' => 'Farm Journal leveraged the popularity of Shark Week to launch an omnichannel campaign that included a digital series, social media content, and a print magazine. The campaign aimed to educate consumers about the importance of aquaculture and promote the benefits of seafood as a sustainable protein source.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/04/Cocoa-Price-750x375.jpg',
                'author' => 'Farm Journal',
                'readTime' => 5,
                'date' => '2024-10-22'
            ],
            [
                'tag' => 'Company News',
                'link' => 'https://www.farmjournal.com/how-farm-journal-achieved-massive-farmer-engagement-during-the-2024-pro-farmer-crop-tour/',
                'title' => 'How Farm Journal Achieved Massive Farmer Engagement During the 2024 Pro Farmer Crop Tour',
                'description' => 'Farm Journal\'s Pro Farmer Crop Tour provided a platform for industry leaders to discuss the latest advancements in crop protection technology and sustainability. Key topics included the integration of precision agriculture solutions and the development of new, environmentally friendly pest control methods.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/04/Cocoa-Price-750x375.jpg',
                'author' => 'Farm Journal',
                'readTime' => 8,
                'date' => '2024-10-22'
            ],
            [
                'tag' => 'Industry Trends',
                'link' => 'https://nairametrics.com/2024/10/21/stripe-buys-stable-coin-platform-bridge-in-1-1-billion-deal/',
                'title' => 'Stripe Buys Stable Coin Platform Bridge in $1.1 Billion Deal',
                'description' => 'Stripe has acquired Bridge, a stablecoin platform, for $1.1 billion. This move positions Stripe as a key player in the cryptocurrency market and positions it to capitalize on the growing demand for digital currency solutions.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/10/Stripe-Buys-Stable-Coin-Platform-Bridge-in-1-1-Billion-Deal.jpg',
                'author' => 'Nairametrics',
                'readTime' => 5,
                'date' => '2024-10-21'
            ],
            [
                'tag' => 'Industry Trends',
                'link' => 'https://nairametrics.com/2024/10/21/gold-xauusd-surges-past-2700-in-new-record-high/',
                'title' => 'Gold (XAUUSD) Surges Past $2,700 in New Record High',
                'description' => 'Gold prices have surged past $2,700, setting a new record high. This development highlights the impact of geopolitical tensions and economic uncertainty on the demand for safe-haven assets.',
                'image' => 'https://nairametrics.com/wp-content/uploads/2024/10/Gold-XAUUSD-Surges-Past-2700-in-New-Record-High.jpg',
                'author' => 'Nairametrics',
                'readTime' => 6,
                'date' => '2024-10-21'
            ],
        ];

        foreach ($news as $item) {
            News::create($item);
        }
    }
}