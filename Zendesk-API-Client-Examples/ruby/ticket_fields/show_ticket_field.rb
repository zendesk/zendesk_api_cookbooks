require 'zendesk_api'
require 'json'
require_relative "../lib/client"

field = client.ticket_fields.find(:id => 21486127)

puts field.inspect