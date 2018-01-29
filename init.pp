# Manage nginx webserver
class nginx {
    
	package { 'nginx':
        ensure => '1.4.6-1ubuntu3.8',
	    require => Exec['apt-get update'],
    }
	
	service { 'nginx':
        require => Package['nginx'],
	    ensure => 'running',
		enable => true,#load it at boot time
    }
	
	file { ['/var/www','/var/www/cat-pictures']:
		ensure => 'directory',
		owner  => 'vagrant',
		group  => 'vagrant',
		mode   => '0755',
    }
	
	file { '/var/www/cat-pictures/index.html' :
        source => 'puppet:///modules/nginx/index.html',
		require => File['/var/www','/var/www/cat-pictures']
    }
	
	file { '/etc/nginx/sites-enabled/default':
		source => 'puppet:///modules/nginx/cat-pictures.conf',
		notify => Service['nginx'],
		require => File['/var/www','/var/www/cat-pictures']
	}
}