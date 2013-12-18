def client
  FooClient.instance
end

class FooClient < ZendeskAPI::Client
  def self.instance
    @instance ||= new do |config|
      config.url = ENV['ZD_URL']
      config.username = ENV['ZD_USER']
      config.password = ENV['ZD_PASS']

      config.retry = true

      require 'logger'
      config.logger = Logger.new(STDOUT)
    end
  end
end