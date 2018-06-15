vcl 4.0;
import std;

# Default backend definition. Set this to point to your content server.
backend default {
    .host = "127.0.0.1";
    .port = "8080";
}

# List of hosts that allowed to clear cache by http query
acl purgelist {
    "localhost";
    "127.0.0.1";
    "::1";
}

# Cache clearing subroutine - serve BAN BAN_ALL PURGE queries
sub cache_clearing {
    if (req.http.x-method == "BAN" || req.http.x-method == "BAN_ALL" || req.http.x-method == "PURGE") {
        if (!client.ip ~ purgelist) {
              return(synth(403, "Not allowed."));
        }
        if (req.http.x-method == "BAN") {
            ban("obj.http.x-depends-on ~ "  + req.http.x-tag);
            return(synth(200, "Ban added"));
        }
        if (req.http.x-method == "BAN_ALL") {
            ban("req.http.host ~ .*");
            return(synth(200, "Full cache cleared"));
        }
        if (req.method == "PURGE") {
            return (purge);
        }
    }
}

# vcl_recv - serve cache clearing requests and strip cookies
sub vcl_recv {
    # API requests is not using cookies in our conception - so we need to strip cookies for global cache every request
    unset req.http.cookie;

    call cache_clearing;
}

# hash cache identification
#sub vcl_hash {
#    if(req.http.accept) {
#        hash_data(req.http.accept);
#    }
#}

# cache backend response
sub vcl_backend_response {
    # there, if we have special cache header in backend response - we cache this response for needed time
  	if(beresp.http.x-cache){
  		unset beresp.http.set-cookie;
  		unset beresp.http.Vary;
  		set beresp.ttl = std.duration(beresp.http.x-cache + "s", 1h);
  		# remove service cache headers
  		# unset beresp.http.x-tag;
  		# unset beresp.http.x-cache;
  	} else {
  		set beresp.uncacheable = true;
  	}
  	return(deliver);
}

sub vcl_deliver {
}