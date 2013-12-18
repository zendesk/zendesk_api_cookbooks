require 'zendesk_api'
require 'json'

client = ZendeskAPI::Client.new do |config|
  config.url = ENV['ZD_URL']
  config.username = ENV['ZD_USER']
  config.password = ENV['ZD_PASS']
  config.retry = true
  require 'logger'
  config.logger = Logger.new(STDOUT)
end

ticket = client.tickets.mark_as_spam(:id => 190, :verb => :put)
puts ticket.inspect