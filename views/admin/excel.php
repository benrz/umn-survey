<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=answer.xls");
?>

<table>
    <thead>
        <tr>
            <th>E-Mail Address</th>
            <?php foreach ($formQuestionNames as $indexFormQuestionName => $formQuestionName): ?>
                <th><?= $formQuestionName ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($answers as $indexAnswer => $answer): ?>
        <tr>
            <td><?= $indexAnswer ?></td>
            <?php foreach ($answer as $indexAnswerDetail => $answerDetail): ?>
                <?php foreach ($answerDetail as $indexAnswerDetailValue => $answerDetailValue): ?>
                    <td><?= $answerDetailValue ?></td>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>