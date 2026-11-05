<!DOCTYPE html><html class="dark" lang="zh-CN" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<!-- lux:seo:start -->
<title>松露提拉米苏 | LuxurEat</title>
<meta name="description" content="松露提拉米苏是传统甜点的精致演绎，将经典提拉米苏的绵密奶香与松露的浓郁香气融合，呈现独特而优雅的风味。">
<!-- lux:seo:end -->
<!-- Fonts -->

<!-- lux:fonts:start -->
<link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/KingHwaOldSong-rituals-critical.woff2?v=20261104-mobile-grids-flips'); ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/LuxurEatZhiSong-rituals-critical.woff2?v=20261104-mobile-grids-flips'); ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/MaterialSymbolsOutlined-subset.ttf?v=20261104-mobile-grids-flips'); ?>" as="font" type="font/ttf" crossorigin>
<style data-lux-critical-fonts>@font-face{font-family:"KingHwa Page Critical";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/KingHwaOldSong-rituals-critical.woff2?v=20261104-mobile-grids-flips'); ?>") format("woff2");font-weight:700;font-style:normal;font-display:swap}@font-face{font-family:"KingHwa Old Song Site";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/KingHwaOldSong-site.woff2?v=20261104-mobile-grids-flips-site-font7'); ?>") format("woff2");font-weight:700;font-style:normal;font-display:swap}@font-face{font-family:"ZhiSong Page Critical";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/LuxurEatZhiSong-rituals-critical.woff2?v=20261104-mobile-grids-flips'); ?>") format("woff2");font-weight:400;font-style:normal;font-display:swap}@font-face{font-family:"LuxurEat ZhiSong Site";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/LuxurEatZhiSong-site.woff2?v=20261104-mobile-grids-flips-site-font7'); ?>") format("woff2");font-weight:400;font-style:normal;font-display:swap}html[lang^="zh"]{--lux-page-heading:"KingHwa Page Critical","KingHwa Old Song Site"!important;--lux-zh-headline:"KingHwa Page Critical","KingHwa Old Song Site"!important;--lux-zh-body:"ZhiSong Page Critical","LuxurEat ZhiSong Site"!important}</style>
<!-- lux:fonts:end -->
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/tailwind-site.css?v=20261104-mobile-grids-flips'); ?>">
<style>
        body {
            background-color: #000000;
            color: #e5e2e1;
            -webkit-font-smoothing: antialiased;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        .glass-nav {
            background: rgba(19, 19, 19, 0.8);
            backdrop-filter: blur(20px);
        }
        .hero-gradient {
            background: linear-gradient(to bottom, transparent 0%, #000000 100%);
        }
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 1s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal-on-scroll.active {
            opacity: 1;
            transform: translateY(0);
        }
        .line-draw {
            width: 0;
            transition: width 1.5s ease-out;
        }
        .line-draw.active {
            width: 100px;
        }
    </style>


<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/newsletter.css?v=20261104-mobile-grids-flips'); ?>">
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/rituals.css?v=20261104-mobile-grids-flips-recipe2'); ?>">
<link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png'); ?>">
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/mobile-sections.css?v=20261104-mobile-grids-flips'); ?>">
<link rel="ard" href="https://luxureat.cn/.well-known/ard.json">
<?php wp_head(); ?>
</head>
<body class="lux-recipe-page bg-background text-on-background">
<!-- lux:header:start -->
<header class="lux-header">
  <a class="lux-brand" href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>"><img width="64" height="64" loading="eager" fetchpriority="high" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo-64.webp'); ?>" srcset="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo-64.webp'); ?> 64w, <?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo-96.webp'); ?> 96w" sizes="(max-width: 1080px) 96px, 64px" alt="LuxurEat"></a>
  <nav class="lux-nav" aria-label="navigation"><a href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>">首页</a><a href="<?php echo esc_url(luxureat_static_url('zh/about-us', '')); ?>">关于我们</a><a href="<?php echo esc_url(luxureat_static_url('zh/product', '')); ?>">我们的产品</a><a class="active" href="<?php echo esc_url(luxureat_static_url('zh/recipe', '')); ?>">食谱艺术</a><a href="<?php echo esc_url(luxureat_static_url('zh/brand', '')); ?>">品牌新闻</a><a href="<?php echo esc_url(luxureat_static_url('zh/gallery', '')); ?>">企业画廊</a><a href="<?php echo esc_url(luxureat_static_url('zh/blog', '')); ?>">知识博客</a><a href="<?php echo esc_url(luxureat_static_url('zh/certification', '')); ?>">品质认证</a><a href="<?php echo esc_url(luxureat_static_url('zh/china-market-insights', '')); ?>">中国市场</a><a href="<?php echo esc_url(luxureat_static_url('zh/import-export-services', '')); ?>">中国服务</a><a href="<?php echo esc_url(luxureat_static_url('zh/cooperation', '')); ?>">商务合作</a><a href="<?php echo esc_url(luxureat_static_url('zh/contact', '')); ?>">联系我们</a></nav>
  <div class="lux-actions">
    <span class="lux-lang"><a class="active" href="<?php echo esc_url(luxureat_static_url('recipe/truffle-tiramisu')); ?>">ZH</a><span>/</span><a href="<?php echo esc_url(luxureat_static_url('en/recipe/truffle-tiramisu')); ?>">EN</a></span>
    <button class="lux-menu" type="button" data-open="关闭" data-closed="菜单" aria-expanded="false">菜单</button>
  </div>
</header>
<!-- lux:header:end -->
<?php if (!luxureat_cms_detail('zh-recipe-truffle-tiramisu', 'zh', 'recipe')): ?><main class="lux-article-page"><nav class="lux-breadcrumb" aria-label="面包屑导航"><a href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>">首页</a><span aria-hidden="true">/</span><a href="<?php echo esc_url(luxureat_static_url('zh/recipe', '')); ?>">食谱艺术</a><span aria-hidden="true">/</span><span aria-current="page">松露提拉米苏</span></nav><article class="lux-recipe-reader lux-recipe-seo-page"><section class="lux-recipe-hero"><figure><img width="1000" height="667" loading="eager" fetchpriority="high" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/journal/recipe-truffle-tiramisu.webp'); ?>" alt="松露提拉米苏"></figure><div class="lux-recipe-intro"><span>甜点食谱</span><h1 id="lux-reader-title">松露提拉米苏</h1><p>松露提拉米苏是传统甜点的精致演绎，将经典提拉米苏的绵密奶香与松露的浓郁香气融合，呈现独特而优雅的风味。</p><dl class="lux-recipe-facts"><div><dt>时间</dt><dd>30分钟</dd></div><div><dt>难度</dt><dd>中等</dd></div><div><dt>份量</dt><dd>4人份</dd></div></dl></div></section><section class="lux-recipe-body"><aside class="lux-recipe-ingredients"><h2>食材</h2><ul><li>250克马斯卡彭奶酪</li><li>2个新鲜鸡蛋（蛋黄和蛋白分开）</li><li>60克糖</li><li>100克手指饼干</li><li>100毫升浓缩咖啡</li><li>1汤匙咖啡利口酒（可选）</li><li>5-6片薄薄的黑松露片</li><li>1茶匙松露油（可选，用于增强香气）</li><li>适量无糖可可粉</li><li>饼干或巧克力片，用于装饰</li></ul></aside><div class="lux-recipe-method"><h2>准备</h2><ol><li>准备咖啡：将咖啡煮好后放凉。如果需要，可以加入利口酒。</li><li>制作马斯卡彭松露奶油：将蛋黄和糖打发至颜色变浅，加入马斯卡彭奶酪并轻轻搅拌。另将蛋白打发至硬性发泡，从底部向上拌入蛋黄混合物，最后淋上少许松露油。</li><li>将浸泡过咖啡的手指饼干和马斯卡彭奶油交替铺在模具或糕点圈中，并在每层之间放入薄薄的松露片。</li><li>最后铺上一层奶油，撒上无糖可可粉，再用松露碎屑和酥脆饼干屑装饰。</li><li>放入冰箱冷藏至少2小时后再食用。</li></ol></div></section><section class="lux-recipe-nutrition"><header><h2>每份的估计营养成分</h2></header><dl><div><dt>能量</dt><dd>1300千焦（约310千卡）</dd></div><div><dt>饱和脂肪</dt><dd>7克（35%）</dd></div><div><dt>碳水化合物（糖类）</dt><dd>20克</dd></div><div><dt>蛋白质</dt><dd>7克</dd></div></dl><figure class="lux-recipe-video"><video controls playsinline preload="none" poster="<?php echo esc_url(get_template_directory_uri() . '/assets/media/journal/recipe-truffle-tiramisu.webp'); ?>" aria-label="松露提拉米苏"><source src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/recipes/truffle-tiramisu.mp4'); ?>" type="video/mp4"></video><figcaption>层层制作的提拉米苏，承载意式甜点的柔软与惊喜。</figcaption></figure><div class="lux-recipe-extra-videos"><video style="flex-grow: 1.777778; aspect-ratio: 1.777778" data-lux-autoplay muted loop playsinline preload="none" controls aria-label="松露提拉米苏 1"><source data-src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/products/videos/scene-19905874.mp4'); ?>" type="video/mp4"></video><video style="flex-grow: 0.527473; aspect-ratio: 0.527473" data-lux-autoplay muted loop playsinline preload="none" controls aria-label="松露提拉米苏 2"><source data-src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/products/videos/truffle-oil-pour-portrait.mp4'); ?>" type="video/mp4"></video><video style="flex-grow: 1.777778; aspect-ratio: 1.777778" data-lux-autoplay muted loop playsinline preload="none" controls aria-label="松露提拉米苏 3"><source data-src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/products/videos/refresh-19551103.mp4'); ?>" type="video/mp4"></video></div></section><section class="lux-recipe-details"><div><dt>参考产区</dt><dd>参考：威尼托甜点的创意表达</dd></div><div><dt>推荐用油</dt><dd>此食谱以松露油为风味点缀，不建议过量</dd></div><div><dt>过敏原提示</dt><dd>含蛋、奶、麸质；咖啡利口酒可能含酒精</dd></div><div><dt>可替换食材</dt><dd>马斯卡彭可用奶油奶酪；手指饼可按包装替换为无麸质版本</dd></div><div><dt>相关产品</dt><dd><a class="lux-recipe-product-link" href="<?php echo esc_url(luxureat_static_url('zh/product', '?category=truffle#product-catalogue')); ?>">松露产品目录（以实际库存和标签为准）</a></dd></div></section></article></main><?php endif; ?>
<!-- lux:footer:start -->
<section class="lux-newsletter" aria-labelledby="lux-newsletter-title-zh">
  <div class="lux-newsletter-inner">
    <div class="lux-newsletter-intro"><img width="1024" height="1024" class="lux-newsletter-icon" loading="eager" fetchpriority="low" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/newsletter-envelope.svg'); ?>" alt="" aria-hidden="true"><div class="lux-newsletter-copy"><h2 id="lux-newsletter-title-zh">LUXUREAT（露意膳）通讯｜订阅我们的更新</h2><p>接收产品上新、品牌活动与意式风味资讯。提交后，请通过邮件完成确认。</p></div></div>
    <form class="lux-newsletter-form" data-newsletter-form novalidate>
      <label class="lux-visually-hidden" for="lux-newsletter-email-zh">请输入电子邮箱</label>
      <div><input id="lux-newsletter-email-zh" name="email" type="email" autocomplete="email" maxlength="120" placeholder="请输入电子邮箱"><button type="submit">确认订阅</button></div>
      <input name="company" type="text" tabindex="-1" autocomplete="off" hidden aria-hidden="true">
      <p data-newsletter-feedback role="status" aria-live="polite" data-invalid="请输入正确的邮箱格式。
Please enter a valid email address."></p>
    </form>
  </div>
</section>
<footer class="lux-footer">
  <div class="lux-footer-grid">
    <div class="lux-footer-brand"><div class="lux-footer-brand-main"><img width="256" height="256" loading="eager" fetchpriority="low" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png'); ?>" alt="LuxurEat"><p>不止于进口，更致力于定义意大利高端美食在中国的新标准。<br>LuxurEat（露意膳）以正宗风味为根，以品质与安全为准则，将意大利饮食文化与创新体验带到中国。</p></div><div class="lux-footer-legal"><button type="button" class="lux-with-icon" data-footer-modal="privacy"><svg class="lux-lucide lux-inline-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.5 3.8 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>隐私政策</button><button type="button" class="lux-with-icon" data-footer-modal="cookie"><svg class="lux-lucide lux-inline-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"></path><circle cx="8.5" cy="8.5" r=".5" fill="currentColor"></circle><circle cx="9" cy="15" r=".5" fill="currentColor"></circle><circle cx="15" cy="13" r=".5" fill="currentColor"></circle></svg>Cookie政策</button><button type="button" class="lux-with-icon" data-footer-modal="terms"><svg class="lux-lucide lux-inline-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>销售条款</button><button type="button" class="lux-with-icon" data-footer-modal="shipping"><svg class="lux-lucide lux-inline-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path><path d="M15 18H9"></path><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path><circle cx="17" cy="18" r="2"></circle><circle cx="7" cy="18" r="2"></circle></svg>配送说明</button></div></div>
    <nav><a href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>">首页</a><a href="<?php echo esc_url(luxureat_static_url('zh/about-us', '')); ?>">关于我们</a><a href="<?php echo esc_url(luxureat_static_url('zh/product', '')); ?>">我们的产品</a><a href="<?php echo esc_url(luxureat_static_url('zh/recipe', '')); ?>" aria-current="page">食谱艺术</a><a href="<?php echo esc_url(luxureat_static_url('zh/brand', '')); ?>">品牌新闻</a><a href="<?php echo esc_url(luxureat_static_url('zh/gallery', '')); ?>">企业画廊</a><a href="<?php echo esc_url(luxureat_static_url('zh/blog', '')); ?>">知识博客</a><a href="<?php echo esc_url(luxureat_static_url('zh/certification', '')); ?>">品质认证</a><a href="<?php echo esc_url(luxureat_static_url('zh/china-market-insights', '')); ?>">中国市场</a><a href="<?php echo esc_url(luxureat_static_url('zh/import-export-services', '')); ?>">中国服务</a><a href="<?php echo esc_url(luxureat_static_url('zh/cooperation', '')); ?>">商务合作</a><a href="<?php echo esc_url(luxureat_static_url('zh/contact', '')); ?>">联系我们</a></nav>
    <div class="lux-footer-social"><a href="https://xhslink.com/m/AfATtrqiQvu" target="_blank" rel="noopener"><img width="200" height="200" loading="eager" fetchpriority="low" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/rednote.svg'); ?>" alt="">小红书</a><button type="button" data-footer-modal="wechat"><img width="229" height="200" loading="eager" fetchpriority="low" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/wechat.svg'); ?>" alt="">微信</button><a href="https://v.douyin.com/9H5RI6LEdaU" target="_blank" rel="noopener"><img width="200" height="200" loading="eager" fetchpriority="low" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/douyin.svg'); ?>" alt="">抖音</a><a href="https://weibo.com/u/6353448966" target="_blank" rel="noopener"><img width="200" height="200" loading="eager" fetchpriority="low" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/weibo.svg'); ?>" alt="">微博</a></div>
    <div><a href="mailto:china@luxureat.com">china@luxureat.com</a><a href="mailto:roberto@ugolinigroup.com">roberto@ugolinigroup.com</a><a href="tel:+8615721452475">+86 15721452475</a></div>
  </div>
  <div class="lux-footer-bottom">© 2026 LuxurEat（露意膳）｜露意膳（上海）贸易有限公司 版权所有 ｜ 统一社会信用代码：91310000MAERED2X1W</div>
</footer>
<!-- lux:footer:end -->
<!-- lux:scripts:start -->





<!-- lux:scripts:end --><?php wp_footer(); ?>
</body></html>
