require 'zendesk_api'
require 'json'
require_relative "../lib/client"

tickets = client.tickets(:path => "problems/autocomplete", :text => "test", :verb => :post)

put tickets.fetch
