# AdSense

https://developers.google.com/adsense/management/

AdSenseパッケージを作ろうかと思ったけど意外と多機能なので保留中。  
https://github.com/google/google-api-php-client-services/tree/master/src/Google/Service/AdSense

レポートをChatWorkに通知するだけのデモ。

web側からは何もない。

- Socialiteで認証してaccess_tokenとrefresh_tokenを取得。
- tokenをenvで設定。
- 後はartisanコマンドをタスクスケジュールで自動実行してるだけ。

## メモ
AdSense APIはサービスアカウントでは使えない。
https://developers.google.com/adsense/management/getting_started?hl=ja#register
