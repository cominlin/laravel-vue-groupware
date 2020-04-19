export const defaultLoginData = {
  id: 0,
  name: '',
  kana: '',
  email: '',
  avatar: '',
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
    language: 'ja',
    type: 1,
  },
  groups: []
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