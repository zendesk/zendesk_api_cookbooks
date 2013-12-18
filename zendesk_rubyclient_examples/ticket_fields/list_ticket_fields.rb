require 'zendesk_api'
require 'json'
require_relative "../lib/client"

client.ticket_fields.each do |field|
  puts field.inspect
end