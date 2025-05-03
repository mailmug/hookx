/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 54b0ffc3af871b189435266df516f7575c1b9675 */


ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_hookx_add_callback, 0, 1, IS_VOID, 0)
    ZEND_ARG_TYPE_INFO(0, callback, IS_MIXED, 0)   
    ZEND_ARG_TYPE_INFO(0, priority, IS_LONG, 1)       
    ZEND_ARG_TYPE_INFO(0, idx, IS_STRING, 1)          
    ZEND_ARG_TYPE_INFO(0, accepted_args, IS_LONG, 1)   
ZEND_END_ARG_INFO()


ZEND_BEGIN_ARG_INFO_EX(arginfo_hookx_get_callback_func, 0, 0, 1)
    ZEND_ARG_INFO(0, data_zval)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_hookx_get_callback_arg_num, 0, 0, 1)
    ZEND_ARG_INFO(0, data_zval)
ZEND_END_ARG_INFO()


ZEND_METHOD(Hookx, add_callback);
ZEND_METHOD(Hookx, get_callback_func);
ZEND_METHOD(Hookx, get_callback_arg_num);

static const zend_function_entry hookx_methods[] = {
    PHP_ME(Hookx, add_callback, arginfo_hookx_add_callback, ZEND_ACC_PUBLIC)
    PHP_ME(Hookx, get_callback_func, arginfo_hookx_get_callback_func, ZEND_ACC_PUBLIC)
    PHP_ME(Hookx, get_callback_arg_num, arginfo_hookx_get_callback_arg_num, ZEND_ACC_PUBLIC)
    PHP_FE_END
};