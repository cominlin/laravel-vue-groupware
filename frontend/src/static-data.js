export const defaultLoginData = {
  id: 0,
  name: '',
  kana: '',
  email: '',
  avatar: '',
  timezone: 'Asia/Tokyo',
  language: 'ja',
  type: 1,
  groups: []
}

export const defaultUserFormData = {
  user: {
    id: 0,
    name: '',
    kana: '',
    email: '',
    timezone: 'Asia/Tokyo',
    language: 'ja',
    type: 1,
  },
  groups: []
}

export const notificationTypes = {
  ScheduleCreate: {
    icon: 'mdi-calendar',
  },
  ScheduleEdit: {
    icon: 'mdi-calendar',
  },
  ScheduleRemove: {
    icon: 'mdi-calendar',
  },
  ScheduleComment: {
    icon: 'mdi-calendar',
  }
}

export const languageOptions = {
  en: {
    text: 'English',
    value: 'en'
  },
  ja: {
    text: '日本語',
    value: 'ja'
  },
  tw: {
    text: '繁體中文',
    value: 'tw'
  }
}

export const userAuthorities = [
  { text: 'authority.normal', value: 1 },
  { text: 'authority.admin', value: 2 },
  { text: 'authority.super_admin', value: 3 },
  { text: 'authority.inactive', value: 0 },
]

export const imageBaseUrl = location.protocol + '//' + location.host + '/image_get/'

export const timeZoneOptions = [
  {
    text: '(GMT-11:00) ミッドウェイ島 (Midway)',
    value: 'Pacific/Midway'
  },
  {
    text: '(GMT-10:00) ハワイ (Hawaii)',
    value: 'US/Hawaii'
  },
  {
    text: '(GMT-10:00/-09:00) アダク (Adak)',
    value: 'America/Adak'
  },
  {
    text: '(GMT-09:00) ガンビア (Gambier)',
    value: 'Pacific/Gambier'
  },
  {
    text: '(GMT-09:00/-08:00) アンカレッジ (Anchorage)',
    value: 'America/Anchorage'
  },
  {
    text: '(GMT-08:00) ピットケアンズ (Pitcairn)',
    value: 'Pacific/Pitcairn'
  },
  {
    text: '(GMT-08:00/-07:00) ロサンゼルス (Los Angeles)',
    value: 'America/Los_Angeles'
  },
  {
    text: '(GMT-07:00) フェニックス (Phoenix)',
    value: 'America/Phoenix'
  },
  {
    text: '(GMT-07:00/-06:00) デンバー (Denver)',
    value: 'America/Denver'
  },
  {
    text: '(GMT-06:00) テグシガルパ、マナグア (Tegucigalpa)',
    value: 'America/Tegucigalpa'
  },
  {
    text: '(GMT-06:00/-05:00) メキシコシティ、シカゴ (Mexico City)',
    value: 'America/Mexico_City'
  },
  {
    text: '(GMT-05:00) パナマ (Panama)',
    value: 'America/Panama'
  },
  {
    text: '(GMT-05:00/-04:00) ニューヨーク、トロント (New York)',
    value: 'America/New_York'
  },
  {
    text: '(GMT-04:00) カラカス、ラパス (Caracas)',
    value: 'America/Caracas'
  },
  {
    text: '(GMT-04:00/-03:00) サンティアゴ (Santiago)',
    value: 'America/Santiago'
  },
  {
    text: '(GMT-03:50/-02:30) セントジョンズ (St. Johns)',
    value: 'America/St_Johns'
  },
  {
    text: '(GMT-03:00) ブエノスアイレス (Buenos Aires)',
    value: 'America/Argentina/Buenos_Aires'
  },
  {
    text: '(GMT-03:00/-02:00) サンパウロ、ミケロン (Miquelon)',
    value: 'America/Miquelon'
  },
  {
    text: '(GMT-02:00) サウスジョージア (South Georgia)',
    value: 'Atlantic/South_Georgia'
  },
  {
    text: '(GMT-01:00) カーボベルデ (Cape Verde)',
    value: 'Atlantic/Cape_Verde'
  },
  {
    text: '(GMT-01:00/GMT) アゾレス諸島 (Azores)',
    value: 'Atlantic/Azores'
  },
  {
    text: '(GMT) レイキャビク、ヌアクショット (Reykjavik)',
    value: 'Atlantic/Reykjavik'
  },
  {
    text: '(GMT/GMT+01:00) ロンドン、カサブランカ (London)',
    value: 'Europe/London'
  },
  {
    text: '(GMT+01:00) キンシャサ、ルアンダ (Kinshasa)',
    value: 'Africa/Kinshasa'
  },
  {
    text: '(GMT+01:00/+02:00) パリ、ベルリン、ローマ、アムステルダム (Paris)',
    value: 'Europe/Paris'
  },
  {
    text: '(GMT+02:00) カイロ (Cairo)',
    value: 'Africa/Cairo'
  },
  {
    text: '(GMT+02:00/+03:00) ビリニュス、キエフ (Vilnius)',
    value: 'Europe/Vilnius'
  },
  {
    text: '(GMT+03:00) モスクワ、ミンスク、アンタナナリボ (Moscow)',
    value: 'Europe/Moscow'
  },
  {
    text: '(GMT+03:30/04:30) テヘラン (Tehran)',
    value: 'Asia/Tehran'
  },
  {
    text: '(GMT+04:00) ドバイ、マスカット (Dubai)',
    value: 'Asia/Dubai'
  },
  {
    text: '(GMT+04:30) カブール (Kabul)',
    value: 'Asia/Kabul'
  },
  {
    text: '(GMT+05:00) サマルカンド、カラチ (Samarkand)',
    value: 'Asia/Samarkand'
  },
  {
    text: '(GMT+05:30) コロンボ、ニューデリー (Colombo)',
    value: 'Asia/Colombo'
  },
  {
    text: '(GMT+05:45) カトマンズ (Kathmandu)',
    value: 'Asia/Kathmandu'
  },
  {
    text: '(GMT+06:00) アルマトイ、ビシュケク (Almaty)',
    value: 'Asia/Almaty'
  },
  {
    text: '(GMT+06:30) ヤンゴン (Yangon)',
    value: 'Asia/Yangon'
  },
  {
    text: '(GMT+07:00) バンコク、ホーチミン (Bangkok)',
    value: 'Asia/Bangkok'
  },
  {
    text: '(GMT+08:00) 上海、香港、台北、シンガポール (Shanghai)',
    value: 'Asia/Shanghai'
  },
  {
    text: '(GMT+08:45) ユークラ (Eucla)',
    value: 'Australia/Eucla'
  },
  {
    text: '(GMT+09:00) 東京、ソウル (Tokyo)',
    value: 'Asia/Tokyo'
  },
  {
    text: '(GMT+09:30) ダーウィン (Darwin)',
    value: 'Australia/Darwin'
  },
  {
    text: '(GMT+09:30/+10:30) アデレード (Adelaide)',
    value: 'Australia/Adelaide'
  },
  {
    text: '(GMT+10:00) ポートモレスビー、グアム (Port Moresby)',
    value: 'Pacific/Port_Moresby'
  },
  {
    text: '(GMT+10:00/+11:00) シドニー (Sydney)',
    value: 'Australia/Sydney'
  },
  {
    text: '(GMT+10:30/+11:00) ロードハウ (Lord Howe)',
    value: 'Australia/Lord_Howe'
  },
  {
    text: '(GMT+11:00) スレドネコリムスク、サハリン (Srednekolymsk)',
    value: 'PAsia/Srednekolymsk'
  },
  {
    text: '(GMT+12:00) マジュロ、ウェイク、フィジー (Majuro)',
    value: 'Pacific/Majuro'
  },
  {
    text: '(GMT+12:00/+13:00) オークランド (Auckland)',
    value: 'Pacific/Auckland'
  },
  {
    text: '(GMT+12:45/13:45) チャタム (Chatham)',
    value: 'Pacific/Chatham'
  },
  {
    text: '(GMT+13:00) ファカオフォ (Fakaofo)',
    value: 'Pacific/Fakaofo'
  },
  {
    text: '(GMT+13:00/+14:00) アピア (Apia)',
    value: 'Pacific/Apia'
  },
  {
    text: '(GMT+14:00) キリティマティ (Kiritimati)',
    value: 'Pacific/Kiritimati'
  },
]