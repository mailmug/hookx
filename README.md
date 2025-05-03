# Hookx

**Hookx** is a PHP extension that optimizes WordPress by handling hooks in C for lower memory usage and faster performance.

## Features

- Native C implementation of WordPress-style hooks
- Lower memory footprint compared to PHP-based hook systems
- Faster callback execution
- Ideal for high-performance WordPress environments

## Installation

```bash
phpize
./configure --enable-hookx
make
sudo make install
