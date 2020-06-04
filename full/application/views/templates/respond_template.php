<div>
    <div style="width:100%; height:52px; background: #2196f3;"> <a href="<?php echo base_url(); ?>"> <img style="width: 64px; float:left; padding-right: 5px; display: block; overflow: auto;" src="https://evolutionschool.pp.ua/evolution-logo.18813f54.png" alt="evolution-logo">
            <div style="float:left;">
                <p style="color:darkblue; font-weight: bold; margin: 5px 0 3px;">Evolution</p>
                <p style="color:goldenrod; font-weight: bold; margin: 0;"> Language School </p>
            </div>
        </a>
    </div>
    <p>Здравствуйте!</p>
    <p>Меня зовут <?php echo $name ?></p>
    <p>Мой номер: <a href="tel:<?php echo $phone ?>"><?php echo $phone ?></a></p>
    <p>Моя почта: <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a></p>
    <?php if (mb_strlen($comment) > 0) { ?>
        <p><?php echo $comment ?></p>
    <?php }
    if ($source_info['id'] != 0) { ?>
        <p>Нашел вас через: <?php
                            if (!isset($source_info['friend'])) {
                                $source_info['friend'] = '';
                            } elseif (!empty($source_info['friend'])) {
                                $source_info['type_name'] = 'друга';
                            }
                            echo $source_info['type_name'] . '<strong> ' . $source_info['friend'] . '</strong>'; ?><img style="width: 25px;" src="<?php echo base_url() . '/logo/' . $source_info['type_logo']; ?>" alt="<?php echo $source_info['type_name']; ?>"></p>
    <?php }
    $test_results = (array) json_decode($test_result);

    if (!empty($test_results)) { ?>
        <p>Результаты тестов:</p>
        <?php
        $num = 0;
        $correct_num = 0;
        foreach ($test_results as $test_result) {
            $num++;
            if ($test_result[2] == 'fail') {
                $color = 'red';
                $icon = '&#10008;';
            } else {
                $color = 'green';
                $icon = '&#10004;';
                $correct_num++;
            }
        ?>
            <p><?php echo $num; ?>) <?php echo $test_result[1]; ?> - <span style="color:<?php echo $color; ?>; font-weight: bold; text-transform:uppercase;"><?php echo $icon . ' ' . $test_result[2]; ?></span></p>
        <?php
        }
        $correct_percent = round(($correct_num / $num) * 100, 2);
        ?>
        <p style="font-weight: bold;">Правильные ответы <?php echo $correct_num . '(' . $correct_percent . '%) из ' . $num; ?></p>
    <?php } ?>
</div>