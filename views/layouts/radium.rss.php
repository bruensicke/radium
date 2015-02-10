<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0">
    <channel>
        <title><?= $this->scaffold->human; ?> RSS Feed</title>
        <description></description>
        <link><?= $this->url('/', array('absolute' => true)); ?></link>
        <lastBuildDate><?= date('D, d M Y g:i:s O'); ?></lastBuildDate>
        <pubDate><?= date('D, d M Y g:i:s O'); ?></pubDate>
        <?= $this->content(); ?>
    </channel>
</rss>