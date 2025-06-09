<?php

/**
 * Template Name: Our History
 */

get_header();

if (post_password_required()) :

    echo get_the_password_form();

else :

$image_root = get_template_directory_uri() . '/images/history/';
$thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
$images = [
    "intro" => [
        "hero-banner-min.png",
        "dude-min.png",
    ],
    "20s" => [
        "20s-main-image-min.png",
        "20s-icecream-truck-min.png",
        "20s-letter-min.png",
        "ExpandedRubberCo-Ad-Onazote1929-min.png",
        "20s-factory-min.png",
        "20s-brochure-min.png",
        "another-dude-min.png",
    ],
    "40s" => [
        "1940s-screen-01-min.png",
        "40s-military-min.png",
        "40s-plane-min.png",
        "40s-dudes-min.png",
        "expanded-rubber-co-1950-min.png",
        "scott-poster-min.png",
        "40s-plane-1-min.png",
        "life-jacket-min.png",
    ],
    "60s" => [
        "1960s-main-image-min.png",
        "jenson-min.png",
        "40-years.png",
    ],
    "80s" => [
        "1980+90s-screen-01-min.png",
        "prince-philip-min.png",
    ],
    "00s" => [
        "2000s-main-image-min.png",
        "CA_5931_ZF_May_CA2_0009-min.png",
        "kentucky-factory-min.png",
        "kentucky-min.png",
        "US-factory-min.png",
    ],
    "10s" => [
        "2000s-screen-03-min.png",
        "azote-2000s-01-768x576-min.png",
        "Nike-Trainers-min.png",
        "azote-2000s-03-1-768x576-min.png",
        "ECS-merged2-768x733-min.png",
        "Polish-factory-min.png",
    ]
];


?>

<div class="zf-history">

    <!-- Intro Section -->
    <header class="zf-history__intro black-bg white-text image-cover" style="--bg-img: url('<?php echo $image_root . $images['intro'][0]; ?>');">

        <section class="zf-history__panel zf-history__intro-primary" data-js-el="scroll-target" data-js-fx="mb">
            <div class="cont-xs text-center fade-in">
                <h1 class="screen-reader-text">Our History</h1>
                <h2 class="uppercase h1">100&nbsp;Years of&nbsp;Innovation<br />
                    <span class="font-alt">100&nbsp;Years of&nbsp;History</span>
                </h2>
                <p class="cont-xxs uppercase margin-t-40 h6 zf-history__text" style="max-width: 36em;">Zotefoams has a rich and vibrant history spanning more than 100 years that starts in the uk with tyres and rubber and moves through innovation into new products and global markets.</p>
            </div>
        </section>

        <section class="zf-history__panel zf-history__intro-secondary" data-js-el="scroll-target">
            <div class="cont-m text-center is-sticky" style="max-width: 68em;">
                <img src="<?php echo $image_root . $images['intro'][1]; ?>" alt="Placeholder" />
                <h3 class="uppercase font-alt h3 zf-history__text">Zotefoams is the direct descendant of onazote limited, which was founded by charles marshall in 1921 when he patented a process to manufacture hard and soft expanded rubbers.</h3>
                <div class="cont-xxs zf-history__text margin-t-40 margin-b-20" style="max-width: 44em; ">
                    <p>He was inspired by the work of three austrian brothers - hans, fritz, and herman pfleumer - who conceived the concept of filling tyres with an expanded lightweight material, rather than air.</p>
                    <p>Although the concept of the puncture-proof automotive tyre was unsuccessful, the experimentation highlighted the potential of this novel material for a multitude of applications.</p>
                </div>
            </div>
        </section>

    </header>

    <div class="zf-history__years">

        <!-- Sticky Navigation for Date Ranges -->
        <nav aria-label="Timeline Navigation" data-js-el="scroll-target">
            <ul>
                <li><a href="#years-1920s">1920<span class="zf-history__minimise">s</span><span class="zf-history__separator">-</span><span class="zf-history__minimise">19</span>30s</a></li>
                <li><a href="#years-1940s">1940<span class="zf-history__minimise">s</span><span class="zf-history__separator">-</span><span class="zf-history__minimise">19</span>50s</a></li>
                <li><a href="#years-1960s">1960<span class="zf-history__minimise">s</span><span class="zf-history__separator">-</span><span class="zf-history__minimise">19</span>70s</a></li>
                <li><a href="#years-1980s">1980<span class="zf-history__minimise">s</span><span class="zf-history__separator">-</span><span class="zf-history__minimise">19</span>90s</a></li>
                <li><a href="#years-2000s"><span class="zf-history__minimise">20</span>00s</a></li>
                <li><a href="#years-2010s"><span class="zf-history__minimise">20</span>10s<span class="zf-history__minimise"><span class="zf-history__separator">-</span>today</span></a></li>
            </ul>
        </nav>

        <!-- 1920s - 1930s -->
        <section id="years-1920s" class="zf-history__section tint-bg theme-tint image-cover" style="--bg-img: url('<?php echo $image_root . $images['20s'][0]; ?>');">
            <header class="zf-history__panel" data-js-el="scroll-target">
                <div class="cont-xs zf-history__year-intro h2 is-sticky">
                    <h2 class="font-alt h2">1920s - 1930s</h2>
                    <p class="uppercase h2">The search for<br />
                        optimal materials</p>
                </div>
            </header>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m  is-sticky">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['20s'][1]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['20s'][2]; ?>" alt="" />
                        <button class="zf-history__popup-marker" aria-describedby="popup-1920s-01">
                            <div class="zf-history__popup" role="tooltip" aria-hidden="true" id="popup-1920s-01">The letter is from Charles Marshall to T Wall and Sons, who were developing their ice cream business.</div>
                        </button>
                        <img src="<?php echo $image_root . $images['20s'][3]; ?>" alt="" />
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p>In 1925, the company was renamed The Expanded Rubber Company Limited, with Onazote remaining as a brand name for expanded rubber decades to come. That same year saw the first recorded use of Onazote for refrigeration.
                        <p>Caption: The letter (left) is from Charles Marshall to T Wall and Sons, who were developing their ice cream business.
                        <p>In 1927, the company moved from its original north London home to the 50,000sq.ft Palace of Arts in Wembley, which was originally constructed for the 1924-25 British Empire Exhibition.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols zf-history__cols--swap cont-m  is-sticky">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['20s'][4]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['20s'][5]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['20s'][6]; ?>" alt="" />
                        <button class="zf-history__popup-marker" aria-describedby="popup-1920s-02">
                            <div class="zf-history__popup" role="tooltip" aria-hidden="true" id="popup-1920s-02">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.</div>
                        </button>

                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <h4 class="uppercase font-alt h4">Success Beckons</h4>
                        <p>In 1935 the company moved to a former cable works in Mitcham Road, Croydon, where Zotefoams headquarters and main manufacturing site are still located today.</p>
                        <p>With commercial success still proving elusive, ownership of the company passed to the St Helens Cable and Rubber Company in 1938. This marked a turning point, with a new Managing Director, Henry Shelmerdine, entrusted with reorganising and equipping the business.</p>
                        <p>Within a year, production was steady at half a ton (over 500kg) per week of Onazote and Rubazote - hard and soft expanded rubber. Insulation in food industry applications and seals and gaskets were key markets.</p>
                        <p>Prosperity followed, and the new management team was able to convince government departments that the company and its materials were to be relied upon.</p>
                    </div></div>
                </div>
            </article>
        </section>

        <!-- 1940s - 1950s -->
        <section id="years-1940s" class="zf-history__section black-bg white-text theme-dark image-cover" style="--bg-img: url('<?php echo $image_root . $images['40s'][0]; ?>');">
            <header class="zf-history__panel" data-js-el="scroll-target">
                <div class="cont-xs zf-history__year-intro h2 is-sticky">
                    <h2 class="font-alt h2">1940s - 1950s</h2>
                    <p class="uppercase h2">Innovation and<br />
                        Expansion in support<br />
                        Of the nation</p>
                </div>
            </header>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['40s'][1]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['40s'][2]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['40s'][3]; ?>" alt="" />
                        <button class="zf-history__popup-marker" aria-describedby="popup-1940s-01">
                            <div class="zf-history__popup" role="tooltip" aria-hidden="true" id="popup-1940s-01">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p><strong>Wartime saw a rapid increase in demand for Onazote and Rubazote, primarily in marine buoyancy and aviation applications.</strong></p>
                        <p>Shelmerdine's drive and initiative was fully tested in the service of the nation. New processes were developed for making a special soft rubber - Aerozote- used for self-sealing aircraft fuel tanks.</p>
                        <p>New factories were opened in Slough and Dundee, to manufacture defence products such as booms and trailing cables, insulation for military containers and, most intriguingly, superstructures for midget submarines.</p>
                        <p>With global supply chains impacted by the conflict, the company's most vital raw material, natural rubber, was in desperately short supply.</p>
                        <p>That challenge was a driver for innovation and an early indicator of the search for optimal materials that is the hallmark of modern-day Zotefoams.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols zf-history__cols--swap cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['40s'][4]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['40s'][5]; ?>" alt="" />

                        <button class="zf-history__popup-marker" aria-describedby="popup-1940s-02">
                            <div class="zf-history__popup" role="tooltip" aria-hidden="true" id="popup-1940s-02">
                                Granules of FUF, driven by studio wind machines, provided the realistic blizzard effects for the iconic film, Scott of the Antarctic.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p>Developments included Formvar, an expanded vinyl with great impact strength, and FUF - expanded urea-formaldehyde resin. This looked like snow and was used to create the blizzard effects in the famous 1948 adventure film Scott of the Antarctic.</p>
                        <p>This early work in exploring the potential of expanded plastics attracted the interest of BX Plastics Limited, which acquired the Expanded Rubber Company in 1943, subsequently transferring ownership in 1948 to parent company the British Xylonite Company Limited.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['40s'][6]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['40s'][7]; ?>" alt="" />

                        <button class="zf-history__popup-marker" aria-describedby="popup-1940s-03">
                            <div class="zf-history__popup" role="tooltip" aria-hidden="true" id="popup-13">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>

                        <button class="zf-history__popup-marker" aria-describedby="popup-1940s-03">
                            <div class="zf-history__popup zf-history__popup--alt" role="tooltip" aria-hidden="true" id="popup-13">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <h4 class="uppercase h4 font-alt">A World Of Applications</h4>
                        <p>In the early 1950s the Expanded Rubber Company was the largest company in the world completely engaged in the manufacture of expanded materials.</p>
                        <p>The Sales Department was expanded, new equipment and a new laboratory installed and agents appointed in all major industrial nations.</p>
                        <p>With a secure supply of rubber now available, Onazote and Rubazote were once again the main products, but growth was also supported by innovation. A sponge rubber - Zote - was promoted alongside a microcellular shoe soling material, while Rubacurl (a complementary product to Rubazote) bonded lightweight latex with hair to give extra strength in product protection applications.</p>
                    </div></div>
                </div>
            </article>
        </section>

        <!-- 1960s - 1970s -->
        <section id="years-1960s" class="zf-history__section tint-bg theme-tint image-cover" style="--bg-img: url('<?php echo $image_root . $images['60s'][0]; ?>');">
            <header class="zf-history__panel" data-js-el="scroll-target">
                <div class="cont-xs zf-history__year-intro h2 is-sticky">
                    <h2 class="font-alt h2">1960s - 1970s</h2>
                    <p class="uppercase h2">Introducing<br />
                        Plastazote® and Evazote®<br />
                        - and the end of rubber</p>
                </div>
            </header>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols zf-history__cols--swap cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['60s'][1]; ?>" alt="" />

                        <button class="zf-history__popup-marker" aria-describedby="popup-1960s-01">
                            <div class="zf-history__popup" role="tooltip" aria-hidden="true" id="popup-1960s-01">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p><strong>In 1962, Plastazote® was launched. This now-iconic brand, the foundation of the AZOTE® polyolefin foams, is produced from polyethylene and used in a vast range of applications.</strong></p>
                        <p>In its infancy, Plastazote was in great demand for medical and healthcare applications, such as neck and body splints, orthotics and limb supports. This was thanks to the purity of the materials, derived from the unique three-stage manufacturing process.</p>
                        <p>1968 saw the introduction of Evazote® EVA copolymer foam, a further world- class product, boasting additional toughness and resilience.</p>
                        <p>Plastazote was specified by famous brands such as the Jensen Company, which used the material for impact and energy absorption in its revered Interceptor.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['60s'][2]; ?>" alt="" />
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p>The success of Plastazote and Evazote, together with difficult trading conditions, led to the decision in 1974 to cease production of rubber materials. The expanded polystyrene business was also sold, enabling BXL to focus on the potential of Plastazote and Evazote.</p>
                        <p>By the end of the 1970s, demand for these materials resulted in four-shift working. The company was acquired by British Petroleum (BP) in 1978 and remained part of the Chemicals division for the next 15 years. This was a period of significant investment in modern production machinery to meet increasing demand and broaden the product range. Plastazote LD24, at the time the lightest foam of its type in the world, was launched in 1984, opening up many new applications.</p>
                        <p>When Rubazote was finally discontinued, it had been in production for nearly 40 years.</p>
                    </div></div>
                </div>
            </article>
        </section>


        <!-- 1980s - 1990s -->
        <section id="years-1980s" class="zf-history__section black-bg white-text theme-dark image-cover" style="--bg-img: url('<?php echo $image_root . $images['80s'][0]; ?>');">
            <header class="zf-history__panel" data-js-el="scroll-target">
                <div class="cont-xs zf-history__year-intro h2 is-sticky">
                    <h2 class="font-alt h2">1980s - 1990s</h2>
                    <p class="uppercase h2">A New Era And The Birth Of&nbsp;Zotefoams Plc</p>
                </div>
            </header>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['80s'][1]; ?>" alt="" />

                        <button class="zf-history__popup-marker" aria-describedby="popup-1980s-01">
                            <div class="zf-history__popup" role="tooltip" aria-hidden="true" id="popup-1980s-01">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p><strong>1981, Plastazote® achieved royal recognition when it received the Prince Philip Award for polymers in the service of mankind. The Award was presented by its namesake, a champion of British technology and industry.</strong></p>
                        <p>The next decade saw continuing growth and success on the global stage, firmly establishing Plastazote and Evazote® as the world's leading technical foam brands.</p>
                        <p>In 1992 a management buyout established Zotefoams Limited; this was followed by a flotation on the London Stock Exchange (ZTF:LON) in 1995, which gave birth to Zotefoams plc.</p>
                        <p>International growth continued following the flotation, with the establishment of a North American sales subsidiary, Zotefoams Inc, to meet rapidly growing demand with local service.</p>
                    </div></div>
                </div>
            </article>
        </section>

        <!-- 2000s -->
        <section id="years-2000s" class="zf-history__section tint-bg theme-tint image-cover" style="--bg-img: url('<?php echo $image_root . $images['00s'][0]; ?>');">
            <header class="zf-history__panel" data-js-el="scroll-target">
                <div class="cont-xs zf-history__year-intro h2 is-sticky">
                    <h2 class="font-alt h2">2000s</h2>
                    <p class="uppercase h2">The High-Performance Decade</p>
                </div>
            </header>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols zf-history__cols--swap cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['00s'][1]; ?>" alt="" />

                        <button class="zf-history__popup-marker" aria-describedby="popup-2000s-01">
                            <div class="zf-history__popup zf-history__popup--alt" role="tooltip" aria-hidden="true" id="popup-2000s-01">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p><strong>As the new millennium dawned, opportunities and optimism were abundant - but on the night of October 22, 2000, a fire at the Mitcham Road site caused damage running into millions of pounds and destroyed a third of the factory.</strong></p>
                        <p>No cause was ever identified but, as the company rebuilt, safety was, and remains to this day, the primary consideration.</p>
                        <p>In the aftermath of the fire, the management team, then led by Group CEO David Stirling, reassessed the prospects for the business. The development of the ZOTEK® High-Performance Products (HPP) portfolio traces its origins back to that time, with the decision to leverage the capabilities of the three-stage process for new, advanced materials. These unique materials would meet demanding regulatory and application requirements, using Zotefoams' technology and knowhow.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['00s'][2]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['00s'][3]; ?>" alt="" />

                        <button class="zf-history__popup-marker" aria-describedby="popup-2000s-02">
                            <div class="zf-history__popup zf-history__popup--alt" role="tooltip" aria-hidden="true" id="popup-2000s-02">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p>Rebuilding in the UK, the company completed new headquarters, manufacturing and R&D buildings.</p>
                        <p>In 2001, Zotefoams Inc moved into a purpose-built facility in Kentucky, strategically located for ease of access to major manufacturing hubs in the USA. ZOTEK F 30, produced from PVDF polymer and the first commercial grade in the HPP family, was launched in 2004, followed in 2008 by the first ZOTEK N nylon foam.</p>
                        <p>Also in 2008, T-TUBES - now T-FIT® - technical insulation range was developed, which harnesses the properties of ZOTEK materials for demanding applications in cleanrooms, aseptic and general industrial areas.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols zf-history__cols--swap cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['00s'][4]; ?>" alt="" />
                        <button class="zf-history__popup-marker" aria-describedby="popup-2000s-03">
                            <div class="zf-history__popup zf-history__popup--alt" role="tooltip" aria-hidden="true" id="popup-2000s-03">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p>As well as developing and manufacturing outstanding new products, the company continued to expand its global reach. In 2007, Zotefoams appointed its first distributor in Asia, and in 2013 entered a 50/50 joint venture with the Japanese INOAC Corporation to manufacture and sell AZOTE® polyolefin products in Asia and Australasia.</p>
                        <p>In 2008 Zotefoams established a stake in Massachusetts-based MuCell Extrusion Technology LLC, a joint venture formed to exploit and license a proprietary microcellular foaming technology for extrusion processes. Four years later and as MuCell Extrusion LLC (MEL), the company became a wholly owned Zotefoams subsidiary.</p>
                    </div></div>
                </div>
            </article>
        </section>

        <!-- 2010s - Today -->
        <section id="years-2010s" class="zf-history__section black-bg white-text theme-dark image-cover" style="--bg-img: url('<?php echo $image_root . $images['10s'][0]; ?>');">
            <header class="zf-history__panel" data-js-el="scroll-target">
                <div class="cont-xs zf-history__year-intro h2 is-sticky">
                    <h2 class="font-alt h2">2010s - Today</h2>
                    <p class="uppercase h2">A New Era and the Birth of Zotefoams Plc</p>
                </div>
            </header>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['10s'][1]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['10s'][2]; ?>" alt="" />
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p><strong>The past decade has been one of accelerating change and growth for Zotefoams.</strong></p>
                        <p>The HPP range has continued to develop, to great acclaim. ZOTEK F is the lightweight material of choice for aircraft interiors, its versatility lending itself to many applications, in the cabin and behind the panels. The range includes NASA-approved grades, used across all current space programmes.</p>
                        <p>The ZOTEK N family now incorporates a lighter grade and in 2012, ZOTEK PEBA, a foamed Polyether block amide designed for the footwear industry, was introduced. The product is now famously the basis of Zotefoams' exclusive and record-breaking partnership with Nike.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols zf-history__cols--swap cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['10s'][3]; ?>" alt="" />
                        <img src="<?php echo $image_root . $images['10s'][4]; ?>" alt="" />
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <h4 class="uppercase h4 font-alt">2015 T-fit® Unique Insulation Technology</h4>
                        <p>Alongside the development of outstanding new products, Zotefoams has increased its global presence through new subsidiaries and joint venture partnerships. A 2015 joint venture in China to manufacture and sell the T-FIT insulation range is now wholly owned and, as Zotefoams T-FIT Material Technology (Kunshan) Co Ltd, enjoying considerable success.</p>
                        <p>In Asia and Australasia, AZOTE polyolefin foams are promoted through a joint venture company based in Hong Kong, bringing the benefits of these exceptional materials to manufacturers in the region and notably the automotive industry.</p>
                        <p>In 2018, Zotefoams Inc extended its facility and added extrusion and a high- pressure (HP) autoclave to complement the existing HTLP capabilities at its site, making the USA manufacture and supply of many AZOTE grades a reality. A second HP autoclave came onstream in 2020.</p>
                    </div></div>
                </div>
            </article>

            <article class="zf-history__panel" data-js-el="scroll-target">
                <div class="zf-history__cols cont-m">
                    <div class="zf-history__images">
                        <img src="<?php echo $image_root . $images['10s'][5]; ?>" alt="" />
                        <button class="zf-history__popup-marker" aria-describedby="popup-2010s-01">
                            <div class="zf-history__popup zf-history__popup--alt" role="tooltip" aria-hidden="true" id="popup-2010s-01">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eu consectetur cursus, nisl erat dictum enim, nec cursus erat erat nec enim.
                            </div>
                        </button>
                    </div>
                    <div class="zf-history__text text-center fade-in"><div class="zf-history__text-inner">
                        <p>In 2019, the UK manufacturing site increased capacity for expansion of materials with a new factory housing two large high-temperature low-pressure (HTLP) autoclaves for the expansion of nitrogen-saturated slabs and in the same year, Zotefoams established a T-FIT sales and service centre in Ahmedabad, India to support the growing food and pharmaceutical sectors in the region.</p>
                        <p>In February 2021 the company began operations at a third foam manufacturing site. Located in Brzeg, south west Poland, the site produces the most popular AZOTE grades for customers in Continental Europe.</p>
                        <p>With the capacity investments in the UK, the USA and Poland, Zotefoams has increased its global block foam manufacturing capacity by 60% compared to the position at the end of 2017.</p>
                        <p>Today Zotefoams continues to develop and produce world-class foam products, while expanding its reach as an international organisation.</p>
                    </div></div>
                </div>
            </article>
            <p class="text-center padding-t-b-50"><a href="#page" class="">Back to top</a></p>

        </section>
        <!-- <div id="panel-indicators" class="panel-indicators"></div> -->
<div id="progress-bar"></div>

    </div>
</div>

<?php
endif;

get_footer(); ?>