<div id='wishyouhad'>
        <h1>
    <?php 
        // echo $data['gamertag'].' is missing ';
        // echo $data['achievements']['total']-$data['achievements']['current'];
        // echo '/'.$data['achievements']['total'].' '.$data['games'][0]['title'].' achievements';
    ?></h1>

<?php foreach($wishyouhad as $trophy): ?>
    <section>
        <h2><img src="<?=$trophy['artwork']?>"><?=$trophy['title']?></h2>
        <p class='desc'><?=$trophy['description']?></p>
        <p><?=count($trophy['friends'])?> online friend<?php echo count($trophy['friends']) > 1 ? 's' : ''; ?> ha<?php echo count($trophy['friends']) > 1 ? 've' : 's'; ?> this trophy. You do not.</p>
        <ul>
            <?php foreach($trophy['friends'] as $friend): ?>
            <li><img src="<?=$friend['gamerpic']['large']?>"><?=$friend['gamertag']?></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endforeach;?>
</div>