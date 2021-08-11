<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use GuzzleHttp\Client;
use App\Models\User;

class LineBotController extends Controller
{
  public function lineCrud(Request $request)
  {
    // botインスタンス生成
    $httpClient = new CurlHTTPClient(env('LINE_ACCESS_TOKEN'));
    $lineBot = new LINEBot($httpClient, ['channelSecret' => env('LINE_CHANNEL_SECRET')]);

    // 署名確認
    $signature = $request->header('x-line-signature');
    if (!$lineBot->validateSignature($request->getContent(), $signature)) {
        abort(400, 'Invalid signature');
    }
    $events = $lineBot->parseEventRequest($request->getContent(), $signature);
    foreach($events as $event){
      if (!($event instanceof TextMessage)) {
        continue;
      }
      \Log::debug([$event]);
      $replyToken = $event->getReplyToken();
      $replyText = '';
      $line_userId = $event->getUserId()[0];

      // 処理の確認
      $text = $event->getText();
  
      // 説明
      if($text === '説明'){
        // $replyText = $this->getExplanation();
      }

      // 紐づけ
      if($text === '紐づけ'){
        $this->linkToAccount();
      }
      // 登録

      // 更新
      

      $lineBot->replyText($replyToken, $replyText);
    }
  }

  public function linkToAccount(string $email)
  {
    $user = User::where('email', $email)->first();

    if(is_null($user)){
      $replyText = 'ユーザーが存在しません';
    }else{

    }
  }
}
