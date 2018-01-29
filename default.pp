Exec {
    path => ['/bin', '/usr/bin'],
}

exec { 'apt-get update':
  path => '/usr/bin',
}

file { '/tmp/hello' :
    content => 'hello world!\n',
}

include nginx