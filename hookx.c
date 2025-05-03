/* hookx extension for PHP */

#ifdef HAVE_CONFIG_H
# include <config.h>
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "php_hookx.h"
#include "hookx_arginfo.h"
#include "ext/standard/php_array.h"

#include "ext/standard/php_var.h"


typedef struct _callback_data {
    zval function;
    zend_long accepted_args;
} callback_data;

static void callback_data_dtor(zval *zv) {
	callback_data *data = (callback_data *) Z_PTR_P(zv);
    if (data != NULL) {
        zval_ptr_dtor(&data->function);
        efree(data);
    }
}

zend_class_entry *hookx_ce;

void hookx_register_class(void)
{
    zend_class_entry ce;
    INIT_NS_CLASS_ENTRY(ce, "Hookx", "Action", hookx_methods);
    hookx_ce = zend_register_internal_class(&ce);

    zend_declare_property_null(hookx_ce, "callbacks", sizeof("callbacks")-1, ZEND_ACC_PUBLIC);
}



static int le_callback_data;

static void free_callback_data(zend_resource *rsrc) {
    callback_data *cb = (callback_data *)rsrc->ptr;
    zval_ptr_dtor(&cb->function);  
    efree(cb);  
}


void add_callback_func(zval *object, zval *callback, zend_long accepted_args, zend_long priority, zend_string *idx) {
    zval *callbacks_prop = zend_read_property(hookx_ce, Z_OBJ_P(object), "callbacks", sizeof("callbacks") - 1, 1, NULL);
    ZVAL_DEREF(callbacks_prop);

    zval callbacks_copy;
    if (callbacks_prop && Z_TYPE_P(callbacks_prop) == IS_ARRAY) {
        ZVAL_DUP(&callbacks_copy, callbacks_prop);
        SEPARATE_ARRAY(&callbacks_copy);
    } else {
        array_init(&callbacks_copy);
    }

    zval *priority_val = zend_hash_index_find(Z_ARRVAL(callbacks_copy), priority);
    zval priority_array;
    if (priority_val && Z_TYPE_P(priority_val) == IS_ARRAY) {
        ZVAL_DUP(&priority_array, priority_val);
        SEPARATE_ARRAY(&priority_array);
    } else {
        array_init(&priority_array);
    }

    callback_data *cb = emalloc(sizeof(callback_data));
    ZVAL_COPY(&cb->function, callback);  
    cb->accepted_args = accepted_args;

    zval cb_resource;
    ZVAL_RES(&cb_resource, zend_register_resource(cb, le_callback_data));

    if (zend_hash_update(Z_ARRVAL(priority_array), idx, &cb_resource) == NULL) {
        php_error_docref(NULL, E_WARNING, "Failed to add callback to priority array");
        zval_ptr_dtor(&cb_resource);  
    }

    if (zend_hash_index_update(Z_ARRVAL(callbacks_copy), priority, &priority_array) == NULL) {
        php_error_docref(NULL, E_WARNING, "Failed to update callbacks with priority array");
    }

    zend_update_property(hookx_ce, Z_OBJ_P(object), "callbacks", sizeof("callbacks") - 1, &callbacks_copy);
    zval_ptr_dtor(&callbacks_copy);
}


PHP_METHOD(Hookx, add_callback)
{
    zval *callback;
    zend_long priority = 10;
    zend_string *idx = NULL;  
    zend_long accepted_args = 1;

    ZEND_PARSE_PARAMETERS_START(1, 4)
        Z_PARAM_ZVAL(callback)
        Z_PARAM_OPTIONAL
        Z_PARAM_LONG(priority)
        Z_PARAM_STR(idx)
        Z_PARAM_LONG(accepted_args)
    ZEND_PARSE_PARAMETERS_END();

    add_callback_func(getThis(), callback, accepted_args, priority, idx);
}



PHP_METHOD(Hookx, get_callback_func)
{
    zval *resource_zval;
    zend_resource *res;
    callback_data *data;

    ZEND_PARSE_PARAMETERS_START(1, 1)
        Z_PARAM_ZVAL(resource_zval)
    ZEND_PARSE_PARAMETERS_END();
    
    if (Z_TYPE_P(resource_zval) != IS_RESOURCE) {
        php_error(E_WARNING, "Expected resource parameter");
        RETURN_NULL();
    }

    res = Z_RES_P(resource_zval);

    if (res->type != le_callback_data) {
        php_error(E_WARNING, "Invalid resource type");
        RETURN_NULL();
    }

    data = (callback_data *)res->ptr;

    RETURN_ZVAL(&data->function, 1, 0);
}

PHP_METHOD(Hookx, get_callback_arg_num)
{
	zval *resource_zval;
    zend_resource *res;
    callback_data *data;

    // Parse parameters
    ZEND_PARSE_PARAMETERS_START(1, 1)
        Z_PARAM_ZVAL(resource_zval)
    ZEND_PARSE_PARAMETERS_END();
    
    if (Z_TYPE_P(resource_zval) != IS_RESOURCE) {
        php_error(E_WARNING, "Expected resource parameter");
        RETURN_NULL();
    }

    res = Z_RES_P(resource_zval);

    if (res->type != le_callback_data) {
        php_error(E_WARNING, "Invalid resource type");
        RETURN_NULL();
    }

    data = (callback_data *)res->ptr;

    RETURN_LONG(data->accepted_args);
}


/* {{{ PHP_RINIT_FUNCTION */
PHP_RINIT_FUNCTION(hookx)
{
#if defined(ZTS) && defined(COMPILE_DL_HOOKX)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif

	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION */
PHP_MINFO_FUNCTION(hookx)
{
	php_info_print_table_start();
	php_info_print_table_row(2, "hookx support", "enabled");
	php_info_print_table_end();
}
/* }}} */

/* {{{ PHP_MINIT_FUNCTION */
PHP_MINIT_FUNCTION(hookx)
{
    hookx_register_class();

    le_callback_data = zend_register_list_destructors_ex(
        free_callback_data, // Destructor
        NULL,               // No persistent destructor
        "callback_data",    // Resource name (for debugging)
        module_number
    );

    return SUCCESS;
}
/* }}}*/


/* {{{ hookx_module_entry */
zend_module_entry hookx_module_entry = {
	STANDARD_MODULE_HEADER,
	"hookx",					/* Extension name */
	NULL,					/* zend_function_entry */
	PHP_MINIT(hookx),			/* PHP_MINIT - Module initialization */
	NULL,							/* PHP_MSHUTDOWN - Module shutdown */
	PHP_RINIT(hookx),			/* PHP_RINIT - Request initialization */
	NULL,							/* PHP_RSHUTDOWN - Request shutdown */
	PHP_MINFO(hookx),			/* PHP_MINFO - Module info */
	PHP_HOOKX_VERSION,		/* Version */
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_HOOKX
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(hookx)
#endif

