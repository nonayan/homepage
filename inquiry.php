<?php
  session_start();
  $mode = 'input';
  $errmessage = array();
  if( isset($_POST['back']) && $_POST['back'] ){
    // 何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm'] ){
      // 確認画面
      
    //名前が正しいかどうか
    if( !$_POST['fullname'] ) {
        $errmessage[] = "名前を入力してください";
    } else if( mb_strlen($_POST['fullname']) > 100 ){
        $errmessage[] = "名前は100文字以内にしてください";
    }
      $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

      //貴社名
      $_SESSION['company'] = htmlspecialchars($_POST['company'], ENT_QUOTES);
      
      //emailが正しいかどうか
      if( !$_POST['email'] ) {
          $errmessage[] = "Eメールを入力してください";
      } else if( mb_strlen($_POST['email']) > 200 ){
          $errmessage[] = "Eメールは200文字以内にしてください";
    } else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
        $errmessage[] = "メールアドレスが不正です";
      }
      $_SESSION['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);
      
      $_SESSION['phone_number'] = htmlspecialchars($_POST['phone_number'], ENT_QUOTES);

      //本文欄が正しいかどうか
      if( !$_POST['message'] ){
          $errmessage[] = "お問い合わせ内容を入力してください";
      } else if( mb_strlen($_POST['message']) > 500 ){
          $errmessage[] = "お問い合わせ内容は500文字以内にしてください";
      }
      $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

      //エラー表示
      if( $errmessage ){
        $mode = 'input';
    } else {
        $mode = 'confirm';
    }
  } else if( isset($_POST['send']) && $_POST['send'] ){
    // 送信ボタンを押したとき
    $message  = "お問い合わせを受け付けました \r\n"
              . "名前: " . $_SESSION['fullname'] . "\r\n"
              . "貴社名: " . $_SESSION['company'] . "\r\n"
              . "email: " . $_SESSION['email'] . "\r\n"
              . "電話番号: " . $_SESSION['phone_number'] . "\r\n"
              . "お問い合わせ内容:\r\n"
              . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
      mail($_SESSION['email'],'お問い合わせありがとうございます',$message);
    mail('nonayan2000@gmail.com','お問い合わせありがとうございます',$message);
    $_SESSION = array();
    $mode = 'send';
  } else {
    $_SESSION['fullname'] = "";
      $_SESSION['company'] = "";
    $_SESSION['email']    = "";
      $_SESSION['phone_number'] = "";
    $_SESSION['message']  = "";
  }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="css/inquiry.css">
    <link rel="stylesheet" href="css/reset.css">
</head>

<body>
    <div class="hed">
        <div class="top">
            <ul>
                <li><a href="index.html">トップへ</a></li>
            </ul>
        </div>
        <div class="title">お問い合わせ</div>
    </div>

    <?php if( $mode == 'input' ){ ?>
　　     <div class="box">
            <!-- 入力画面 -->
            <form action="./inquiry.php" method="post">
                <div class="explanation">
                    <div class="Form">
                        <p>お名前　<span class="red">必須</span></p>
                        <input type="text" name="fullname" class="Form-Item-name" value="<?php echo$_SESSION['fullname'] ?>" placeholder="例) 山田太郎"/>
                    </div>
                    <div class="Form">
                        <p class="Form-Item-Label">貴社名</p>
                        <input type="text" name="company" class="Form-Item-text" value="<?php echo$_SESSION['company'] ?>" placeholder="例) ○◯株式会社"/>
                    </div>
                    <div class="Form">
                        <p class="Form-Item-Label">メールアドレス　<span class="red">必須</span></p>
                        <input type="text" name="email" class="Form-Item-text" value="<?php echo $_SESSION['email'] ?>" placeholder="例) example@gmail.com"/>
                    </div>
                    <div class="Form">
                        <p class="Form-Item-Label">電話番号</p>
                        <input type="text" name="phone_number" class="Form-Item-text" value="<?php echo$_SESSION['phone_number'] ?>" placeholder="例) 000-0000-0000"/>
                    </div>
                    <div class="Form">
                        <p class="Form-Item-Label">お問い合わせ内容　<span class="red">必須</span></p>
                        <textarea name="message" class="Form-Item-Textarea"><?php echo $_SESSION['message'] ?></textarea>
                    </div>
                    <!--エラーメッセージ表示-->
                    <?php
                      if( $errmessage ){
                        echo '<div style="color:red;">';
                        echo implode('<br>', $errmessage );
                        echo '</div>';
                      }
                    ?>
                    <input type="submit" name="confirm" class="Form-Btn" value="確認"/>
                </div>
            </form>
            <div class="im">
                    <img src="img/inquiry.png" alt=""><br>
                    <div class="img_text"></div>
            </div>
        </div>
            
    <?php } else if( $mode == 'confirm' ){ ?>
        <!-- 確認画面 -->
        <form action="./inquiry.php" method="post">
            <div class="conf">
                <br><div class="concon">名前</div><br><div class="ph"><?php echo $_SESSION['fullname'] ?></div><br><br>
                <div class="concon">貴社名</div><br><div class="ph"><?php echo $_SESSION['company'] ?></div><br><br>
                <div class="concon">Eメール</div><br><div class="ph"><?php echo $_SESSION['email'] ?></div><br><br>
                <div class="concon">電話番号</div><br><div class="ph"><?php echo $_SESSION['phone_number'] ?></div><br><br>
                <div class="concon">お問い合わせ内容</div><br>
                <div class="ph_2"><?php echo nl2br($_SESSION['message']) ?></div><br><br>
            </div>
            <div class="box_2">
            <input type="submit" class="Form-Btn" name="back" value="戻る" />
            <input type="submit" class="Form-Btn" name="send" value="送信" />
            </div>
        </form>
    <?php } else { ?>
        <!-- 完了画面 -->
        <div class="conf">
            <div class="ph">送信しました。<br>お問い合わせありがとうございました。<br></div>
        </div>
    <?php } ?>
</body>
</html>
