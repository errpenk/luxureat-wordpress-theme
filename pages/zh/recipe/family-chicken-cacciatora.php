<!DOCTYPE html><html class="dark" lang="zh-CN" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<!-- lux:seo:start -->
<title>意式猎人烩鸡 | LuxurEat</title>
<meta name="description" content="鸡腿与洋葱、胡萝卜、西芹、番茄、白葡萄酒和香草慢炖，是中部意大利风格浓郁而适合家庭操作的主菜。">
<!-- lux:seo:end -->
<!-- Fonts -->

<!-- lux:fonts:start -->
<link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/KingHwaOldSong-rituals-critical.woff2?v=20260812-legal-cookie-navigation-59'); ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/LuxurEatZhiSong-rituals-critical.woff2?v=20260812-legal-cookie-navigation-59'); ?>" as="font" type="font/woff2" crossorigin>
<style data-lux-critical-fonts>@font-face{font-family:"KingHwa Page Critical";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/KingHwaOldSong-rituals-critical.woff2?v=20260812-legal-cookie-navigation-59'); ?>") format("woff2");font-weight:700;font-style:normal;font-display:block}@font-face{font-family:"KingHwa Old Song Site";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/KingHwaOldSong-site.woff2?v=20260812-legal-cookie-navigation-59'); ?>") format("woff2");font-weight:700;font-style:normal;font-display:block}@font-face{font-family:"ZhiSong Page Critical";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/LuxurEatZhiSong-rituals-critical.woff2?v=20260812-legal-cookie-navigation-59'); ?>") format("woff2");font-weight:400;font-style:normal;font-display:block}@font-face{font-family:"LuxurEat ZhiSong Site";src:url("<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/LuxurEatZhiSong-site.woff2?v=20260812-legal-cookie-navigation-59'); ?>") format("woff2");font-weight:400;font-style:normal;font-display:block}html[lang^="zh"]{--lux-zh-headline:"KingHwa Page Critical","KingHwa Old Song Site"!important;--lux-zh-body:"ZhiSong Page Critical","LuxurEat ZhiSong Site"!important}</style>
<!-- lux:fonts:end -->
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/tailwind-site.css?v=20260812-legal-cookie-navigation-59'); ?>">
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


<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/newsletter.css?v=20260812-legal-cookie-navigation-59'); ?>">
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/assets/css/rituals.css?v=20260812-legal-cookie-navigation-59'); ?>">
<link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png'); ?>">
<?php wp_head(); ?>
</head>
<body class="bg-background text-on-background">
<!-- lux:header:start -->
<header class="lux-header">
  <a class="lux-brand" href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>"><img width="256" height="256" loading="eager" fetchpriority="high" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png'); ?>" alt="LuxurEat"></a>
  <nav class="lux-nav" aria-label="navigation"><a href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>">首页</a><a href="<?php echo esc_url(luxureat_static_url('zh/about-us', '')); ?>">关于我们</a><a href="<?php echo esc_url(luxureat_static_url('zh/new', '')); ?>">热门新品</a><a href="<?php echo esc_url(luxureat_static_url('zh/product', '')); ?>">系列产品</a><a class="active" href="<?php echo esc_url(luxureat_static_url('zh/recipe', '')); ?>">食谱艺术</a><a href="<?php echo esc_url(luxureat_static_url('zh/brand', '')); ?>">品牌新闻</a><a href="<?php echo esc_url(luxureat_static_url('zh/blog', '')); ?>">知识博客</a><a href="<?php echo esc_url(luxureat_static_url('zh/certification', '')); ?>">品质认证</a><a href="<?php echo esc_url(luxureat_static_url('zh/cooperation', '')); ?>">商务合作</a><a href="<?php echo esc_url(luxureat_static_url('zh/contact', '')); ?>">联系我们</a></nav>
  <div class="lux-actions">
    <a class="lux-icon-action lux-bag-link" href="<?php echo esc_url(luxureat_static_url('zh/bag', '')); ?>" aria-label="购物袋"><svg class="lux-lucide" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg><span class="lux-bag-count" data-bag-count hidden></span></a>
    <button class="lux-icon-action lux-account-link" type="button" data-account-open aria-label="个人登录"><svg class="lux-lucide" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></button>
    <span class="lux-lang"><a class="active" href="<?php echo esc_url(luxureat_static_url('recipe/family-chicken-cacciatora')); ?>">ZH</a><span>/</span><a href="<?php echo esc_url(luxureat_static_url('en/recipe/family-chicken-cacciatora')); ?>">EN</a></span>
    <button class="lux-menu" type="button" data-open="关闭" data-closed="菜单" aria-expanded="false">菜单</button>
  </div>
</header>
<!-- lux:header:end -->
<main class="lux-article-page"><nav class="lux-breadcrumb" aria-label="面包屑导航"><a href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>">首页</a><span aria-hidden="true">/</span><a href="<?php echo esc_url(luxureat_static_url('zh/recipe', '')); ?>">食谱艺术</a><span aria-hidden="true">/</span><span aria-current="page">意式猎人烩鸡</span></nav><article class="lux-recipe-reader lux-recipe-seo-page"><section class="lux-recipe-hero"><figure><img width="1000" height="1000" loading="eager" fetchpriority="high" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/journal/recipe-family-chicken-cacciatora.webp'); ?>" alt="意式猎人烩鸡"></figure><div class="lux-recipe-intro"><span>适合中国家庭的意大利菜</span><h1 id="lux-reader-title">意式猎人烩鸡</h1><p>鸡腿与洋葱、胡萝卜、西芹、番茄、白葡萄酒和香草慢炖，是中部意大利风格浓郁而适合家庭操作的主菜。</p><dl class="lux-recipe-facts"><div><dt>时间</dt><dd>约1小时</dd></div><div><dt>难度</dt><dd>中等</dd></div><div><dt>份量</dt><dd>4人份</dd></div></dl></div></section><section class="lux-recipe-body"><aside class="lux-recipe-ingredients"><h2>食材</h2><ul><li>带骨去皮鸡腿900克</li><li>番茄泥400克</li><li>洋葱100克</li><li>胡萝卜80克</li><li>西芹60克</li><li>特级初榨橄榄油30克</li><li>干白葡萄酒100毫升</li><li>蒜1瓣（约5克）</li><li>迷迭香1枝</li><li>月桂叶1片</li><li>细盐5克</li><li>现磨黑胡椒适量</li></ul></aside><div class="lux-recipe-method"><h2>准备</h2><ol><li>鸡腿擦干，以盐和黑胡椒调味。</li><li>厚底锅加入橄榄油，中高火分批将鸡腿表面煎至金黄。</li><li>鸡腿取出备用，锅中保留油脂。</li><li>加入切碎的洋葱、胡萝卜和西芹，中火炒5–7分钟。</li><li>加入蒜、迷迭香和月桂叶，炒至出香。</li><li>倒入白葡萄酒，刮起锅底焦香物并煮至液体约减半。</li><li>加入番茄泥并搅匀。</li><li>鸡腿放回锅中，酱汁应覆盖鸡肉约一半。</li><li>煮沸后转小火，半盖锅盖炖约30–35分钟。</li><li>期间翻动鸡腿，并按需少量加水。</li><li>确认鸡肉最厚处中心温度达到74°C。</li><li>取出迷迭香和月桂叶。</li><li>按需收浓酱汁并调整盐和黑胡椒。</li><li>静置数分钟后上桌。</li></ol></div></section><section class="lux-recipe-nutrition"><header><h2>每份的估计营养成分</h2></header><dl><div><dt>能量</dt><dd>约1900千焦 / 455千卡</dd></div><div><dt>脂肪</dt><dd>约26克</dd></div><div><dt>其中饱和脂肪</dt><dd>约6克</dd></div><div><dt>碳水化合物</dt><dd>约11克</dd></div><div><dt>其中糖</dt><dd>约7克</dd></div><div><dt>蛋白质</dt><dd>约44克</dd></div><div><dt>膳食纤维</dt><dd>约3克</dd></div><div><dt>钠</dt><dd>约760毫克</dd></div></dl><p class="lux-recipe-nutrition-note"><strong>营养说明</strong>营养数据为每份估算值。</p></section><section class="lux-recipe-details"><div><dt>参考产区</dt><dd>意大利中部，多地区有变体</dd></div><div><dt>推荐用油</dt><dd>结构感中等的特级初榨橄榄油。</dd></div><div><dt>专业提示</dt><dd>鸡肉分批充分上色是风味基础；锅底焦香物必须用葡萄酒或水完整刮起。</dd></div><div><dt>食品安全</dt><dd>不要清洗生鸡肉；处理后清洁双手、砧板与台面。中心温度达到74°C，剩余成品在2小时内冷却冷藏。</dd></div><div><dt>过敏原提示</dt><dd>基础配方不含欧盟14大主要过敏原；葡萄酒可能含亚硫酸盐，番茄泥或高汤需核对标签。</dd></div><div><dt>可替换食材</dt><dd>可用其他带骨鸡腿或整鸡分件；葡萄酒可用低盐高汤或水替代。</dd></div><div><dt>相关产品</dt><dd><a class="lux-recipe-product-link" href="<?php echo esc_url(luxureat_static_url('zh/product', '?category=olive-oil#product-catalogue')); ?>">LuxurEat（露意膳）特级初榨橄榄油产品</a></dd></div></section></article></main>
<!-- lux:footer:start -->
<section class="lux-newsletter" aria-labelledby="lux-newsletter-title-zh">
  <div class="lux-newsletter-inner">
    <div class="lux-newsletter-intro"><img width="1024" height="1024" class="lux-newsletter-icon" loading="lazy" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/newsletter-envelope.svg'); ?>" alt="" aria-hidden="true"><div class="lux-newsletter-copy"><h2 id="lux-newsletter-title-zh">LUXUREAT（露意膳）通讯｜订阅我们的更新</h2><p>接收产品上新、品牌活动与意式风味资讯。提交后，请通过邮件完成确认。</p></div></div>
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
    <div class="lux-footer-brand"><img width="256" height="256" loading="lazy" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png'); ?>" alt="LuxurEat"><p>不止于进口，更致力于定义意大利高端美食在中国的新标准。<br>LuxurEat（露意膳）以正宗风味为根，以品质与安全为准则，将意大利饮食文化与创新体验带到中国。</p></div>
    <nav><a href="<?php echo esc_url(luxureat_static_url('zh', '')); ?>">首页</a><a href="<?php echo esc_url(luxureat_static_url('zh/about-us', '')); ?>">关于我们</a><a href="<?php echo esc_url(luxureat_static_url('zh/new', '')); ?>">热门新品</a><a href="<?php echo esc_url(luxureat_static_url('zh/product', '')); ?>">系列产品</a><a href="<?php echo esc_url(luxureat_static_url('zh/recipe', '')); ?>">食谱艺术</a><a href="<?php echo esc_url(luxureat_static_url('zh/brand', '')); ?>">品牌新闻</a><a href="<?php echo esc_url(luxureat_static_url('zh/blog', '')); ?>">知识博客</a><a href="<?php echo esc_url(luxureat_static_url('zh/certification', '')); ?>">品质认证</a><a href="<?php echo esc_url(luxureat_static_url('zh/cooperation', '')); ?>">商务合作</a><a href="<?php echo esc_url(luxureat_static_url('zh/contact', '')); ?>">联系我们</a></nav>
    <div class="lux-footer-social"><a href="https://xhslink.com/m/AfATtrqiQvu" target="_blank" rel="noopener"><img width="200" height="200" loading="lazy" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/rednote.svg'); ?>" alt="">小红书</a><button type="button" data-footer-modal="wechat"><img width="229" height="200" loading="lazy" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/wechat.svg'); ?>" alt="">微信</button><a href="https://v.douyin.com/9H5RI6LEdaU" target="_blank" rel="noopener"><img width="200" height="200" loading="lazy" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/douyin.svg'); ?>" alt="">抖音</a><a href="https://weibo.com/u/6353448966" target="_blank" rel="noopener"><img width="200" height="200" loading="lazy" decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/social/weibo.svg'); ?>" alt="">微博</a></div>
    <div><a href="mailto:china@luxureat.com">china@luxureat.com</a><a href="mailto:roberto@ugolinigroup.com">roberto@ugolinigroup.com</a><a href="tel:+8615721452475">+86 15721452475</a><div class="lux-footer-legal"><button type="button" data-footer-modal="privacy">隐私政策</button><button type="button" data-footer-modal="cookie">Cookie Policy</button><button type="button" data-footer-modal="terms">销售条款</button><button type="button" data-footer-modal="shipping">配送说明</button></div></div>
  </div>
  <div class="lux-footer-bottom">© 2026 LuxurEat（露意膳）｜露意膳（上海）贸易有限公司 版权所有 ｜ 统一社会信用代码：91310000MAERED2X1W</div>
</footer>
<!-- lux:footer:end -->
<!-- lux:scripts:start -->



<!-- lux:scripts:end --><?php wp_footer(); ?>
</body></html>
