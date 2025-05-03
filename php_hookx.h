/* hookx extension for PHP */

#ifndef PHP_HOOKX_H
# define PHP_HOOKX_H

extern zend_module_entry hookx_module_entry;
# define phpext_hookx_ptr &hookx_module_entry

# define PHP_HOOKX_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_HOOKX)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_HOOKX_H */
