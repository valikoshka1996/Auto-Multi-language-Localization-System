<!--default template slider -->
<!--BEFORE USUNG rename assets/css/_main.css to main.css -->
            <article>
                <img srcset="images/slide01-small.jpg 600w, images/slide01.jpg 1200w" sizes="(max-width: 768px) 100vw, 1200px" src="images/slide01.jpg" alt="" loading="lazy"/>
                <div class="inner">
                    <header>
                        <p><?= $texts['slider_1_1'] ?? 'Main text missing' ?></p>
                        <h2><?= $texts['slider_1_2'] ?? 'Main text missing' ?></h2>
                    </header>
                </div>
            </article>
            <article>
                <img srcset="images/slide02-small.jpg 600w, images/slide02.jpg 1200w" sizes="(max-width: 768px) 100vw, 1200px" src="images/slide02.jpg" alt="" loading="lazy"/>
                <div class="inner">
                    <header>
                        <p><?= $texts['slider_2_1'] ?? 'Main text missing' ?></p>
                        <h2><?= $texts['slider_2_2'] ?? 'Main text missing' ?></h2>
                    </header>
              </div>
         </article>
          <article>
             <img srcset="images/slide03-small.jpg 600w, images/slide03.jpg 1200w" sizes="(max-width: 768px) 100vw, 1200px" src="images/slide03.jpg" alt="" loading="lazy"/>
                <div class="inner">
                   <header>
                       <p><?= $texts['slider_3_1'] ?? 'Main text missing' ?></p>
                        <h2><?= $texts['slider_3_2'] ?? 'Main text missing' ?></h2>
                 </header>
              </div>
         </article>
        </section>