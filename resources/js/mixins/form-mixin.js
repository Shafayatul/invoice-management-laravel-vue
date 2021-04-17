/* eslint-disable no-useless-escape */
import { isEmpty } from '../helpers'

const _regex = {
  name: /^[a-zA-Z\.\- ]{3,32}$/,
  email: /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/,
  phone: /^(\+?[0-9\-]{10,15})$/,
  password: /^.{8,20}$/,
  character: /^[a-zA-Z]+$/
}

const validators = {
  email:                                  v => _regex.email.test(v)           || `Email is not valid.`,
  name:                                   v => _regex.name.test(v)            || `Name can only have characters`,
  password:                               v => _regex.password.test(v)        || `Password can't have less than 8 characters and more than 20 characters`,
  phone:                                  v => _regex.phone.test(v)           || `Please enter a valid phone number.`,
  onlyCharacters:                         v => _regex.character.test(v)       || 'Can\'t have special characters or numbers',
  required:         fieldName        =>   v => !isEmpty(v) || `${fieldName    || 'Field'} is required.`,
  max:              (len, fieldName) =>   v => !(v && v.length > len)         || `${fieldName || 'Field'} can't have more than ${len} characters`,
  min:              (len, fieldName) =>   v => {
    if (typeof v === 'string' && Number.isNaN(+v))
      return !(v && v.length < len) || `${fieldName || 'Field'} can't have less than ${len} characters`
    else if (!Number.isNaN(+v)) return v >= len || `${fieldName || 'Field'} can't be less than ${len}.`
    return true
  },
}

export const createFormMixin = ({ rules = [] }) => {
  const mRules = Object.keys(validators)
    .filter(key => rules.includes(key))
    .reduce((acc, key) =>
      ({ ...acc, [key]: validators[key] }),
      {}
    )
  return {
    name: 'FormMixin',
    data() {
      return {
        rules: mRules,
        fieldAttrs: {
          dense: true,
          outlined: true,
          required: true,
          hideDetails: "auto",
        },
      }
    }
  }
}
