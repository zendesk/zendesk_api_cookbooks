require 'zendesk_api'
require 'json'
require_relative "../lib/client"

puts client.ticket.destroy(:id => 198)