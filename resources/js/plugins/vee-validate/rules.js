

import { Tools } from '@helpers/tools'
import { extend } from 'vee-validate/dist/vee-validate.full.esm'

extend('int', v => Tools.isNumeric(Tools.fromArabicNumber(v)))

extend('mobile', v => Tools.isKsaMobile(v))

export default extend
