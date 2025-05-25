<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Professional video editing and motion graphics services at affordable prices. Boost your marketing with custom videos that convert. Get a free sample video today!">
    <?php wp_head(); ?>
    <style>
        .minimal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background-color:rgba(41, 41, 41, 0.47);
    
}

        .header-left .logo {
    flex: 1;
}

        .header-right {
    flex: 1;
    display: flex;
    justify-content: flex-end; /* Ensures it's at the far right */
    align-items: center;
    gap: 30px;
			max-width: max-content;
      height: 0;
}
.header-center {
    flex: 1;
    text-align: center;
}
        .external-link {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            transition: opacity 0.3s ease;
        }

        .external-link:hover {
            opacity: 0.8;
        }

        .hamburger-icon {
            color: #ffffff;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .minimal-header {
                padding: 15px 20px;
            }
            
            .header-left .logo {
                font-size: 20px;
            }
            
            .external-link {
                font-size: 16px;
            }
            
            .hamburger-icon {
                font-size: 20px;
            }
            .header-center {
              display: none;
            }
			
        }
    </style>
    <script type="application/ld+json">
    {
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Video Editing & Motion Graphics Services",
  "provider": {
    "@type": "Organization",
    "name": "Amiri Motion",
    "url": "https://amirimotion.com/"
  },
  "serviceType": ["Video Editing", "Motion Graphics"],
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Pricing Plans",
    "itemListElement": [
      {
        "@type": "OfferCatalog",
        "name": "Video Editing Pricing Plans",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Standard Video Editing",
              "description": "Suitable for vlogs and simple video edits. Includes 1 revision."
            },
            "url": "https://amirimotion.com/index.php/plans/standard-video",
            "availability": "https://schema.org/InStock",
            "priceSpecification": {
              "@type": "UnitPriceSpecification",
              "price": 15.0,
              "priceCurrency": "USD",
              "eligibleQuantity": {
                "@type": "QuantitativeValue",
                "value": 30,
                "unitText": "Seconds"
              }
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Premium Video Editing",
              "description": "Ideal for reels with a higher number of elements and includes 3 revisions."
            },
            "url": "https://amirimotion.com/index.php/plans/premium-video",
            "availability": "https://schema.org/InStock",
            "priceSpecification": {
              "@type": "UnitPriceSpecification",
              "price": 40.0,
              "priceCurrency": "USD",
              "eligibleQuantity": {
                "@type": "QuantitativeValue",
                "value": 30,
                "unitText": "Seconds"
              }
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Enhanced Video Editing",
              "description": "For complex projects with unlimited revisions."
            },
            "url": "https://amirimotion.com/index.php/plans/enhanced-video",
            "availability": "https://schema.org/InStock",
            "priceSpecification": {
              "@type": "UnitPriceSpecification",
              "price": 60.0,
              "priceCurrency": "USD",
              "eligibleQuantity": {
                "@type": "QuantitativeValue",
                "value": 30,
                "unitText": "Seconds"
              }
            }
          }
        ]
      },
      {
        "@type": "OfferCatalog",
        "name": "Motion Graphics Pricing Plans",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Standard Motion Graphics",
              "description": "Basic animated graphics for simple presentations; includes 1 revision."
            },
            "url": "https://amirimotion.com/index.php/plans/standard-motion-graphics",
            "availability": "https://schema.org/InStock",
            "priceSpecification": {
              "@type": "UnitPriceSpecification",
              "price": 120.0,
              "priceCurrency": "USD",
              "eligibleQuantity": {
                "@type": "QuantitativeValue",
                "value": 30,
                "unitText": "Seconds"
              }
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Premium Motion Graphics",
              "description": "Enhanced motion graphics with additional design elements and 3 revisions."
            },
            "url": "https://amirimotion.com/index.php/plans/premium-motion-graphics",
            "availability": "https://schema.org/InStock",
            "priceSpecification": {
              "@type": "UnitPriceSpecification",
              "price": 180.0,
              "priceCurrency": "USD",
              "eligibleQuantity": {
                "@type": "QuantitativeValue",
                "value": 30,
                "unitText": "Seconds"
              }
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Enhanced Motion Graphics",
              "description": "Comprehensive motion graphics with unlimited revisions for complex projects."
            },
            "url": "https://amirimotion.com/index.php/plans/enhanced-motion-graphics",
            "availability": "https://schema.org/InStock",
            "priceSpecification": {
              "@type": "UnitPriceSpecification",
              "price": 300.0,
              "priceCurrency": "USD",
              "eligibleQuantity": {
                "@type": "QuantitativeValue",
                "value": 30,
                "unitText": "Seconds"
              }
            }
          }
        ]
      }
    ]
  }
}

    </script>
</head>
<body <?php body_class(); ?> style="background-color: #00002c;">
    <header class="minimal-header">
        <div class="header-left">
            <?php if(get_theme_mod('custom_logo')) : ?>
                <img src="<?php echo esc_url(get_theme_mod('custom_logo')); ?>" alt="Logo" class="header-logo">
            <?php else : ?>
                <div class="logo"><a href="https://amirimotion.com" class="external-link">Amiri Motion</a></div>
            <?php endif; ?>
        </div>
		<div class="header-center">
			<h3>
				Video Editing and Motion Graphics services
			</h3>
		</div>
        <div class="header-right">
            <a rel="canonical" href="https://amirimotion.com/index.php/blog/" class="external-link">Blog</a>
            <!-- From Uiverse.io by MuhammadHasann --> 
<button class="free-sample-button" onclick="location.href='https://amirimotion.com/index.php/contact-us'">
  <div class="dots_border"></div>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    class="sparkle"
  >
    <path
      class="path"
      stroke-linejoin="round"
      stroke-linecap="round"
      stroke="black"
      fill="black"
      d="M14.187 8.096L15 5.25L15.813 8.096C16.0231 8.83114 16.4171 9.50062 16.9577 10.0413C17.4984 10.5819 18.1679 10.9759 18.903 11.186L21.75 12L18.904 12.813C18.1689 13.0231 17.4994 13.4171 16.9587 13.9577C16.4181 14.4984 16.0241 15.1679 15.814 15.903L15 18.75L14.187 15.904C13.9769 15.1689 13.5829 14.4994 13.0423 13.9587C12.5016 13.4181 11.8321 13.0241 11.097 12.814L8.25 12L11.096 11.187C11.8311 10.9769 12.5006 10.5829 13.0413 10.0423C13.5819 9.50162 13.9759 8.83214 14.186 8.097L14.187 8.096Z"
    ></path>
    <path
      class="path"
      stroke-linejoin="round"
      stroke-linecap="round"
      stroke="black"
      fill="black"
      d="M6 14.25L5.741 15.285C5.59267 15.8785 5.28579 16.4206 4.85319 16.8532C4.42059 17.2858 3.87853 17.5927 3.285 17.741L2.25 18L3.285 18.259C3.87853 18.4073 4.42059 18.7142 4.85319 19.1468C5.28579 19.5794 5.59267 20.1215 5.741 20.715L6 21.75L6.259 20.715C6.40725 20.1216 6.71398 19.5796 7.14639 19.147C7.5788 18.7144 8.12065 18.4075 8.714 18.259L9.75 18L8.714 17.741C8.12065 17.5925 7.5788 17.2856 7.14639 16.853C6.71398 16.4204 6.40725 15.8784 6.259 15.285L6 14.25Z"
    ></path>
    <path
      class="path"
      stroke-linejoin="round"
      stroke-linecap="round"
      stroke="black"
      fill="black"
      d="M6.5 4L6.303 4.5915C6.24777 4.75718 6.15472 4.90774 6.03123 5.03123C5.90774 5.15472 5.75718 5.24777 5.5915 5.303L5 5.5L5.5915 5.697C5.75718 5.75223 5.90774 5.84528 6.03123 5.96877C6.15472 6.09226 6.24777 6.24282 6.303 6.4085L6.5 7L6.697 6.4085C6.75223 6.24282 6.84528 6.09226 6.96877 5.96877C7.09226 5.84528 7.24282 5.75223 7.4085 5.697L8 5.5L7.4085 5.303C7.24282 5.24777 7.09226 5.15472 6.96877 5.03123C6.84528 4.90774 6.75223 4.75718 6.697 4.5915L6.5 4Z"
    ></path>
  </svg>
  <span class="text_button">GET FREE SAMPLE</span>
</button>

        </div>
        <nav class="main-nav">
            <?php //wp_nav_menu(['theme_location' => 'primary']); ?>
        </nav>
    </header>
